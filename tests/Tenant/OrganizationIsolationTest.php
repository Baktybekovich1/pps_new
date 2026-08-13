<?php

namespace App\Tests\Tenant;

use App\Entity\Institute;
use App\Entity\Organization;
use App\Entity\Stage;
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
 * of the one in the URL, and an admin could create an institute inside someone
 * else's organization.
 */
class OrganizationIsolationTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    private Organization $orgA;
    private Organization $orgB;
    private Teacher $teacherA;
    private Teacher $adminA;
    private Teacher $superAdmin;
    private Stage $stageA;
    private Stage $stageB;

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

        $this->stageA = $this->stage('Stage of Alpha', $this->orgA);
        $this->stageB = $this->stage('Stage of Beta', $this->orgB);
        $this->stage('Shared stage', null);

        $this->em->flush();
    }

    public function testTeacherDoesNotSeeAnotherOrganizationsStages(): void
    {
        $names = $this->stageNamesVisibleTo($this->adminA);

        self::assertContains('Stage of Alpha', $names);
        self::assertNotContains('Stage of Beta', $names);
    }

    public function testStagesWithoutAnOrganizationStayVisibleToEveryone(): void
    {
        // The questionnaire predates multi-tenancy and is still shared; hiding
        // it would empty the rating for every organization at once.
        self::assertContains('Shared stage', $this->stageNamesVisibleTo($this->adminA));
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

    public function testRowsCreatedBySuperAdminStayShared(): void
    {
        // A super admin has not said which organization they are authoring for.
        $this->request('POST', '/api/admin/teacher/questions/stages', $this->superAdmin, ['name' => 'Added by super']);
        self::assertResponseIsSuccessful();

        $created = $this->em->getRepository(Stage::class)->findOneBy(['name' => 'Added by super']);
        self::assertNotNull($created);
        self::assertNull($created->getOrganization());
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

    private function request(string $method, string $path, ?Teacher $as, array $body = null): void
    {
        $headers = ['CONTENT_TYPE' => 'application/json'];
        if (null !== $as) {
            $jwt = static::getContainer()->get(JWTTokenManagerInterface::class)->create($as);
            $headers['HTTP_AUTHORIZATION'] = 'Bearer '.$jwt;
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
            ->setInstitute($institute);
        $this->em->persist($membership);

        return $teacher;
    }

    private function stage(string $name, ?Organization $organization): Stage
    {
        $stage = (new Stage())->setName($name);
        $stage->setOrganization($organization);
        $this->em->persist($stage);

        return $stage;
    }

    private function purge(): void
    {
        $connection = $this->em->getConnection();
        $connection->executeStatement(
            'TRUNCATE teacher_answer, teacher_organization, question_subtitle, question_title,
             stage, institute, teacher, organization RESTART IDENTITY CASCADE'
        );
    }
}
