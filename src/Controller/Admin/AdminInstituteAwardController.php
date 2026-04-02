<?php

namespace App\Controller\Admin;

use App\Dto\AdminFreezeSetAwardDto;
use App\Repository\InstituteAnswerRepository;
use App\Repository\InstituteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[Route('/api/admin/institute/awards')]
#[IsGranted('ROLE_ADMIN')]
#[OA\Tag(name: 'Admin Institute Awards')]
class AdminInstituteAwardController extends AbstractController
{
    public function __construct(
        private readonly InstituteAnswerRepository $instituteAnswerRepository,
        private readonly InstituteRepository $instituteRepository
    ) {}

    #[Route('/{instituteId}', name: 'admin_institute_awards_get', methods: ['GET'])]
    public function getAnswers(int $instituteId): JsonResponse
    {
        $institute = $this->instituteRepository->find($instituteId);
        if (!$institute) {
            return $this->json(['message' => 'Институт не найден'], 404);
        }

        $answers = $this->instituteAnswerRepository->findBy(['institute' => $institute]);
        $result = [];
        foreach ($answers as $answer) {
            $result[] = [
                'id' => $answer->getId(),
                'titleName' => $answer->getSubtitle()->getTitle()->getName(),
                'subtitleName' => $answer->getSubtitle()->getName(),
                'link' => $answer->getLink(),
                'point' => $answer->getSubtitle()->getPoint(),
                'status' => $answer->isActive() ? 'active' : 'freeze',
            ];
        }

        return $this->json($result);
    }

    #[Route('/{action}', name: 'admin_institute_awards_action', methods: ['PUT'], requirements: ['action' => 'freeze|active'])]
    public function action(string $action, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $item) {
            $id = is_array($item) ? ($item['id'] ?? null) : $item;
            if ($id === null) continue;

            $entity = $this->instituteAnswerRepository->find($id);
            if ($entity) {
                $entity->setActive($action === 'active');
                $this->instituteAnswerRepository->save($entity);
            }
        }
        return $this->json(['message' => 'Success']);
    }
}
