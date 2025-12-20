<?php
namespace App\Controller\Auth;

use App\Entity\Teacher;
use App\Model\Step2Request;
use App\Repository\InstituteRepository;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class RegistrationStep2Action extends AbstractController
{
    public function __construct(
        private PositionRepository $positionRepo,
        private InstituteRepository $instituteRepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('/api/me/registration-step2', name: 'me_registration_step2', methods: ['PUT'])]
    public function __invoke(
        #[CurrentUser] Teacher $teacher,
        #[MapRequestPayload] Step2Request $dto
    ): Response {
        // 1. позиция
        $position = $this->positionRepo->find($dto->positionId);
        if (!$position) {
            throw new NotFoundHttpException('Должность не найдена');
        }

        // 2. заполняем простые поля
        $teacher->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setMiddleName($dto->middleName)
            ->setHasTrousers($dto->hasTrousers)
            ->setPosition($position);

        // 3. очищаем старые workplaces (если повторный вызов)
        foreach ($teacher->getTeacherOrganizations() as $to) {
            $this->em->remove($to);
        }
        $this->em->flush(); // удаляем из БД

        // 4. создаём новые
        foreach ($dto->workplaces as $wp) {
            $institute = $this->instituteRepo->find($wp->instituteId);
            if (!$institute || $institute->getOrganization()->getId() !== $wp->organizationId) {
                throw new NotFoundHttpException('Институт не принадлежит указанной организации');
            }

            $to = new \App\Entity\TeacherOrganization();
            $to->setTeacher($teacher)
                ->setOrganization($institute->getOrganization())
                ->setInstitute($institute);
            $this->em->persist($to);
        }

        // 5. считаем teacherTotal в каждом институте
        $this->recalculateTotals($dto->workplaces);

        $this->em->flush();

        return $this->json($teacher, 200, [], ['groups' => 'teacher:read']);
    }

    private function recalculateTotals(array $workplaces): void
    {
        $instIds = array_unique(array_map(fn($wp) => $wp->instituteId, $workplaces));
        foreach ($instIds as $id) {
            $inst = $this->instituteRepo->find($id);
            if ($inst) {
                $cnt = $this->instituteRepo->countTeachers($inst);
                $inst->setTeacherTotal($cnt);
            }
        }
    }
}