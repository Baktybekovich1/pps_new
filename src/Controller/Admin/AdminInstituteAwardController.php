<?php

namespace App\Controller\Admin;

use App\Dto\AdminFreezeSetAwardDto;
use App\Repository\InstituteAnswerRepository;
use App\Repository\InstituteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/institute/awards')]
#[OA\Tag(name: 'Admin Institute Awards')]
class AdminInstituteAwardController extends AbstractController
{
    public function __construct(
        private readonly InstituteAnswerRepository $instituteAnswerRepository,
        private readonly InstituteRepository $instituteRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/{instituteId}', name: 'admin_institute_awards_get', methods: ['GET'])]
    public function getAnswers(int $instituteId): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $institute = $this->instituteRepository->find($instituteId);
        if (!$institute) {
            return $this->json(['message' => 'Институт не найден'], 404);
        }

        $answers = $this->instituteAnswerRepository->findBy(['institute' => $institute]);
        $result = [];
        foreach ($answers as $answer) {
            if (!$answer->getLink()) continue; // only answered
            $result[] = [
                'id'           => $answer->getId(),
                'titleName'    => $answer->getSubtitle()->getTitle()->getName(),
                'subtitleName' => $answer->getSubtitle()->getName(),
                'link'         => $answer->getLink(),
                'point'        => $answer->getSubtitle()->getPoint(),
                'status'       => $answer->isActive() ? 'active' : 'freeze',
            ];
        }

        return $this->json($result);
    }

    #[Route('/{action}', name: 'admin_institute_awards_action', methods: ['PUT'], requirements: ['action' => 'freeze|active'])]
    public function action(string $action, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        foreach ($dto->idBag as $item) {
            $id = is_array($item) ? ($item['id'] ?? null) : $item;
            if ($id === null) continue;

            $entity = $this->instituteAnswerRepository->find($id);
            if ($entity) {
                $entity->setActive($action === 'active');
                $this->entityManager->persist($entity);
            }
        }
        $this->entityManager->flush();

        return $this->json(['message' => 'Success']);
    }
}
