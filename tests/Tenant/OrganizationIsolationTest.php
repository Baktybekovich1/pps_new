<?php

namespace App\Tests\Tenant;

use App\Entity\Institute;
use App\Entity\Organization;
use App\Entity\QuestionSubtitle;
use App\Entity\QuestionTitle;
use App\Entity\Stage;
use App\Entity\TeacherAnswer;
use App\Entity\Years;
use App\Entity\Teacher;
use App\Entity\TeacherOrganization;
use App\Tenant\OrganizationContext;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Covers the rules that keep one organization's data out of another's reach.
 *
 * Every fact here was a real defect at some point: stages leaked because writes
 * were not attributed, a rating page returned the viewer's organization instead
 * of the one in the URL, an admin could create an institute inside someone
 * else's organization, and a teacher of two organizations had their awards
 * counted in both.
 */
class OrganizationIsolationTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    private Organization $orgA;
    private Organization $orgB;
    private Teacher $teacherA;
    private Teacher $teacherAB;
    private Teacher $adminA;
    private Teacher $superAdmin;
    private Stage $stageA;
    private Stage $stageB;
    private QuestionSubtitle $questionA;
    private QuestionSubtitle $questionB;
    private Years $yearA;
    private Years $yearB;
    private \App\Entity\Institute $instituteB;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->purge();

        $this->orgA = $this->organization('Alpha');
        $this->orgB = $this->organization('Beta');

        $instituteA = $this->institute('Institute A', $this->orgA);
        $instituteB = $this->institute('Institute B', $this->orgB);

        $this->teacherA = $this->teacher('teacher-a@example.test', ['ROLE_TEACHER'], $this->orgA, $instituteA);
        $this->adminA = $this->teacher('admin-a@example.test', ['ROLE_TEACHER', 'ROLE_ADMIN'], $this->orgA, $instituteA);
        $this->superAdmin = $this->teacher(
            'super@example.test',
            ['ROLE_TEACHER', 'ROLE_ADMIN', OrganizationContext::ROLE_CROSS_ORGANIZATION],
            $this->orgB,
            $instituteB
        );

        $this->instituteB = $instituteB;
        $this->teacherAB = $this->teacher('teacher-ab@example.test', ['ROLE_TEACHER'], $this->orgA, $instituteA);
        $this->membership($this->teacherAB, $this->orgB, $instituteB);

        $this->stageA = $this->stage('Stage of Alpha', $this->orgA);
        $this->stageB = $this->stage('Stage of Beta', $this->orgB);
        $this->stage('Archived stage', null);

        $this->questionA = $this->question('Question of Alpha', $this->stageA, 10);
        $this->questionB = $this->question('Question of Beta', $this->stageB, 7);

        $this->yearA = $this->year('2026 Alpha', $this->orgA);
        $this->yearB = $this->year('2026 Beta', $this->orgB);

        $this->em->flush();
        // The first request shares this entity manager; start it from the
        // database rather than from half-linked objects built above.
        $this->em->clear();
    }

    public function testTeacherDoesNotSeeAnotherOrganizationsStages(): void
    {
        $names = $this->stageNamesVisibleTo($this->adminA);

        self::assertContains('Stage of Alpha', $names);
        self::assertNotContains('Stage of Beta', $names);
    }

    public function testArchivedStagesAreHiddenFromEveryOrganization(): void
    {
        self::assertNotContains('Archived stage', $this->stageNamesVisibleTo($this->adminA));
    }

    public function testSuperAdminSeesEveryOrganization(): void
    {
        $names = $this->stageNamesVisibleTo($this->superAdmin);

        self::assertContains('Stage of Alpha', $names);
        self::assertContains('Stage of Beta', $names);
    }

    public function testRatingUrlDecidesTheOrganizationRatherThanTheViewer(): void
    {
        $anonymous = $this->ratingOf($this->orgB, null);
        $foreigner = $this->ratingOf($this->orgB, $this->teacherA);

        self::assertSame($anonymous, $foreigner);
    }

    public function testRowsCreatedByAnAdminBelongToTheirOrganization(): void
    {
        $this->request('POST', '/api/admin/teacher/questions/stages', $this->adminA, ['name' => 'Added by Alpha']);
        self::assertResponseIsSuccessful();

        self::assertContains('Added by Alpha', $this->stageNamesVisibleTo($this->adminA));

        $created = $this->em->getRepository(Stage::class)->findOneBy(['name' => 'Added by Alpha']);
        self::assertNotNull($created);
        self::assertSame($this->orgA->getId(), $created->getOrganization()?->getId());
    }

    public function testSuperAdminMustPickAnOrganizationToCreate(): void
    {
        $this->request('POST', '/api/admin/teacher/questions/stages', $this->superAdmin, ['name' => 'Nowhere']);
        self::assertResponseStatusCodeSame(409);

        $this->request('POST', '/api/admin/teacher/questions/stages', $this->superAdmin, ['name' => 'For Alpha'], $this->orgA);
        self::assertResponseIsSuccessful();

        $created = $this->em->getRepository(Stage::class)->findOneBy(['name' => 'For Alpha']);
        self::assertSame($this->orgA->getId(), $created?->getOrganization()?->getId());
    }

    public function testTeacherOfTwoOrganizationsMustSayWhichOneTheyActFor(): void
    {
        $this->request('POST', '/api/teacher/answer', $this->teacherAB, ['subtitleId' => $this->questionA->getId(), 'link' => 'x']);
        self::assertResponseStatusCodeSame(409);

        self::assertSame([], $this->stageNamesInAwardsOf($this->teacherAB, null));
        self::assertSame(['Stage of Beta'], $this->stageNamesInAwardsOf($this->teacherAB, $this->orgB));
    }

    public function testAwardCountsOnlyInTheOrganizationItWasGivenIn(): void
    {
        $this->request('POST', '/api/teacher/answer', $this->teacherAB, ['subtitleId' => $this->questionA->getId(), 'link' => 'x'], $this->orgA);
        self::assertResponseIsSuccessful();

        $answer = $this->em->getRepository(TeacherAnswer::class)->findOneBy(['teacher' => $this->teacherAB]);
        self::assertSame($this->orgA->getId(), $answer?->getOrganization()?->getId());
        self::assertSame($this->yearA->getId(), $answer->getAcademicYear()?->getId());

        self::assertSame(10, $this->ratingTotal($this->orgA, $this->teacherAB));
        self::assertSame(0, $this->ratingTotal($this->orgB, $this->teacherAB));
    }

    public function testFrozenAwardStopsScoring(): void
    {
        $this->request('POST', '/api/teacher/answer', $this->teacherAB, ['subtitleId' => $this->questionA->getId(), 'link' => 'x'], $this->orgA);
        $answerId = $this->em->getConnection()->fetchOne('SELECT id FROM teacher_answer');

        $this->request('PATCH', '/api/admin/teacher/answers/'.$answerId, $this->adminA, ['active' => false]);
        self::assertResponseIsSuccessful();
        self::assertSame(0, $this->ratingTotal($this->orgA, $this->teacherAB));

        $this->request('PATCH', '/api/admin/teacher/answers/'.$answerId, $this->adminA, ['active' => true]);
        self::assertSame(10, $this->ratingTotal($this->orgA, $this->teacherAB));
    }

    public function testAdminCannotModerateAnotherOrganizationsAward(): void
    {
        $this->request('POST', '/api/teacher/answer', $this->teacherAB, ['subtitleId' => $this->questionB->getId(), 'link' => 'x'], $this->orgB);
        $answerId = $this->em->getConnection()->fetchOne('SELECT id FROM teacher_answer');

        $this->request('PATCH', '/api/admin/teacher/answers/'.$answerId, $this->adminA, ['active' => false]);
        self::assertResponseStatusCodeSame(404);
        self::assertTrue((bool) $this->em->getConnection()->fetchOne('SELECT active FROM teacher_answer'));
    }

    public function testTeacherListShowsOnlyTheAdminsOrganization(): void
    {
        $this->request('GET', '/api/teacher/', $this->adminA);
        self::assertResponseIsSuccessful();
        $ids = array_column(json_decode($this->client->getResponse()->getContent(), true), 'teacherId');
        sort($ids);

        $expected = [$this->teacherA->getId(), $this->adminA->getId(), $this->teacherAB->getId()];
        sort($expected);
        self::assertSame($expected, $ids);

        $this->request('GET', '/api/teacher/', null);
        self::assertResponseStatusCodeSame(401);
    }

    public function testAdminCannotAppointADirectorFromAnotherOrganization(): void
    {
        $this->request('POST', '/api/admin/director', $this->adminA, [
            'teacherId' => $this->superAdmin->getId(),
            'instituteId' => $this->em->getConnection()->fetchOne("SELECT id FROM institute WHERE name = 'Institute A'"),
        ]);
        self::assertSame('false', $this->client->getResponse()->getContent());
        self::assertSame(0, (int) $this->em->getConnection()->fetchOne('SELECT count(*) FROM director'));
    }

    public function testQuestionOfAnotherOrganizationCannotBeAnswered(): void
    {
        $this->request('POST', '/api/teacher/answer', $this->teacherAB, ['subtitleId' => $this->questionB->getId(), 'link' => 'x'], $this->orgA);
        self::assertResponseStatusCodeSame(404);
    }

    public function testOrganizationOutsideTheMembershipIsRefused(): void
    {
        $this->request('GET', '/api/award/all', $this->teacherA, null, $this->orgB);
        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminQuestionListShowsOnlyTheirOrganization(): void
    {
        $this->request('GET', '/api/admin/teacher/questions/subtitles', $this->adminA);
        self::assertResponseIsSuccessful();

        $names = array_column(json_decode($this->client->getResponse()->getContent(), true), 'subtitleName');
        self::assertSame(['Question of Alpha'], $names);
    }

    public function testEachOrganizationKeepsItsOwnCurrentYear(): void
    {
        $this->request('POST', '/api/admin/years', $this->adminA, ['name' => '2027 Alpha']);
        self::assertResponseStatusCodeSame(201);

        $current = $this->em->getConnection()->fetchFirstColumn('SELECT name FROM years WHERE is_current ORDER BY id');
        self::assertSame(['2026 Beta', '2027 Alpha'], $current);

        $this->request('GET', '/api/organizations/'.$this->orgB->getId().'/years', null);
        $names = array_column(json_decode($this->client->getResponse()->getContent(), true), 'name');
        self::assertSame(['2026 Beta'], $names);
    }

    public function testTeacherMayChooseWorkplacesInAnyOrganization(): void
    {
        $this->request('PUT', '/api/me/registration-step2', $this->teacherA, [
            'firstName' => 'Test',
            'lastName' => 'Person',
            'middleName' => 'X',
            'positionId' => $this->position()->getId(),
            'workplaces' => [
                ['organizationId' => $this->orgB->getId(), 'instituteId' => $this->instituteB->getId(), 'regular' => true],
            ],
        ]);
        self::assertResponseIsSuccessful();

        $this->em->clear();
        $memberships = $this->em->getRepository(TeacherOrganization::class)->findBy(['teacher' => $this->teacherA->getId()]);
        self::assertSame([$this->orgB->getId()], array_map(static fn ($m) => $m->getOrganization()->getId(), $memberships));
    }

    public function testProfileUpdateCannotTouchSomeoneElse(): void
    {
        $this->request('POST', '/api/teacher/profile/update', $this->teacherA, [
            'id' => $this->adminA->getId(),
            'firstName' => 'Hijacked',
            'lastName' => 'Person',
            'middleName' => 'X',
            'email' => 'admin-a@example.test',
            'positionId' => $this->position()->getId(),
            'institutes' => [],
        ]);
        self::assertResponseIsSuccessful();

        $this->em->clear();
        self::assertSame('Test', $this->em->getRepository(Teacher::class)->find($this->adminA->getId())->getFirstName());
        self::assertSame('Hijacked', $this->em->getRepository(Teacher::class)->find($this->teacherA->getId())->getFirstName());
    }

    public function testOnlySuperAdminMayCreateOrDeleteOrganizations(): void
    {
        $this->request('POST', '/api/admin/organizations', $this->adminA, ['name' => 'Gamma']);
        self::assertResponseStatusCodeSame(403);

        $this->request('DELETE', '/api/admin/organizations/'.$this->orgB->getId(), $this->adminA);
        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminMayEditOnlyTheirOwnOrganization(): void
    {
        $this->request('PUT', '/api/admin/organizations/'.$this->orgA->getId(), $this->adminA);
        self::assertResponseIsSuccessful();

        $this->request('PUT', '/api/admin/organizations/'.$this->orgB->getId(), $this->adminA);
        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminCannotPlantAnInstituteInAnotherOrganization(): void
    {
        $this->request('POST', '/api/admin/institutes', $this->adminA, [
            'name' => 'Smuggled',
            'organization_id' => $this->orgB->getId(),
        ]);
        self::assertResponseStatusCodeSame(403);

        self::assertNull($this->em->getRepository(Institute::class)->findOneBy(['name' => 'Smuggled']));
    }

    public function testAdminRoutesRefuseAPlainTeacher(): void
    {
        foreach (['/api/admin/years', '/api/admin/positions', '/api/admin/teacher/questions/stages'] as $path) {
            $this->request('GET', $path, $this->teacherA);
            self::assertResponseStatusCodeSame(403, $path.' should be closed to a plain teacher');
        }
    }

    public function testAdminRoutesRefuseAnonymousCallers(): void
    {
        $this->request('GET', '/api/admin/years', null);
        self::assertResponseStatusCodeSame(401);
    }

    /**
     * @return string[]
     */
    private function stageNamesVisibleTo(Teacher $teacher): array
    {
        $this->request('GET', '/api/admin/teacher/questions/stages', $teacher);
        self::assertResponseIsSuccessful();

        $payload = json_decode($this->client->getResponse()->getContent(), true);
        $rows = isset($payload[0]) ? $payload : reset($payload);

        return array_column($rows ?: [], 'name');
    }

    private function ratingOf(Organization $organization, ?Teacher $viewer): string
    {
        $this->request('GET', '/api/rating/organization/'.$organization->getId().'/pps', $viewer);
        self::assertResponseIsSuccessful();

        return $this->client->getResponse()->getContent();
    }

    /**
     * @return string[]
     */
    private function stageNamesInAwardsOf(Teacher $teacher, ?Organization $actingFor): array
    {
        $this->request('GET', '/api/award/all', $teacher, null, $actingFor);
        self::assertResponseIsSuccessful();

        return array_column(json_decode($this->client->getResponse()->getContent(), true)['stages'] ?? [], 'stageName');
    }

    private function ratingTotal(Organization $organization, Teacher $teacher): int
    {
        $rating = json_decode($this->ratingOf($organization, null), true);

        return $rating[$teacher->getId()]['total'];
    }

    private function request(string $method, string $path, ?Teacher $as, ?array $body = null, ?Organization $actingFor = null): void
    {
        $headers = ['CONTENT_TYPE' => 'application/json'];
        if (null !== $as) {
            $jwt = static::getContainer()->get(JWTTokenManagerInterface::class)->create($as);
            $headers['HTTP_AUTHORIZATION'] = 'Bearer '.$jwt;
        }
        if (null !== $actingFor) {
            $headers['HTTP_X_ORGANIZATION_ID'] = (string) $actingFor->getId();
        }

        $this->client->request($method, $path, [], [], $headers, null === $body ? null : json_encode($body));
    }

    private function organization(string $name): Organization
    {
        $organization = (new Organization())->setName($name);
        $this->em->persist($organization);

        return $organization;
    }

    private function institute(string $name, Organization $organization): Institute
    {
        $institute = (new Institute())->setName($name)->setOrganization($organization);
        $this->em->persist($institute);

        return $institute;
    }

    private function teacher(string $email, array $roles, Organization $organization, Institute $institute): Teacher
    {
        $teacher = (new Teacher())
            ->setEmail($email)
            ->setFirstName('Test')
            ->setLastName('Person')
            ->setRoles($roles);
        $this->em->persist($teacher);

        $membership = (new TeacherOrganization())
            ->setTeacher($teacher)
            ->setOrganization($organization)
            ->setInstitute($institute)
            ->setRegular(true);
        $this->em->persist($membership);

        return $teacher;
    }

    private function membership(Teacher $teacher, Organization $organization, Institute $institute): void
    {
        $this->em->persist((new TeacherOrganization())
            ->setTeacher($teacher)
            ->setOrganization($organization)
            ->setInstitute($institute)
            ->setRegular(true));
    }

    private function stage(string $name, ?Organization $organization): Stage
    {
        $stage = (new Stage())->setName($name)->setActive(true);
        $stage->setOrganization($organization);
        $this->em->persist($stage);

        return $stage;
    }

    private function question(string $name, Stage $stage, int $point): QuestionSubtitle
    {
        $title = (new QuestionTitle())->setName($name.' title')->setStage($stage);
        $title->setOrganization($stage->getOrganization());
        $this->em->persist($title);

        $question = (new QuestionSubtitle())->setName($name)->setTitle($title)->setPoint($point);
        $question->setOrganization($stage->getOrganization());
        $this->em->persist($question);

        return $question;
    }

    private function year(string $name, Organization $organization): Years
    {
        $year = (new Years())->setName($name)->setIsCurrent(true)->setIsLocked(false);
        $year->setOrganization($organization);
        $this->em->persist($year);

        return $year;
    }

    private function position(): \App\Entity\Position
    {
        $position = (new \App\Entity\Position())->setName('Lecturer');
        $this->em->persist($position);
        $this->em->flush();

        return $position;
    }

    private function purge(): void
    {
        $connection = $this->em->getConnection();
        $connection->executeStatement(
            'TRUNCATE director, teacher_answer, results_of_year, years, teacher_organization, question_subtitle,
             question_title, stage, institute, position, teacher, organization RESTART IDENTITY CASCADE'
        );
    }
}
