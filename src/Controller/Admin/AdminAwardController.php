<?php

namespace App\Controller\Admin;

use App\Dto\AdminFreezeSetAwardDto;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserSocialActivitiesRepository;
use App\Repository\TeacherAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[Route('/api/admin')]
#[IsGranted('ROLE_ADMIN')]
#[OA\Tag(name: 'Admin Awards')]
class AdminAwardController extends AbstractController
{
    public function __construct(
        private readonly UserPersonalAwardsRepository $personalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $researchRepository,
        private readonly UserInnovativeEducationRepository $innovativeRepository,
        private readonly UserSocialActivitiesRepository $socialRepository,
        private readonly TeacherAnswerRepository $teacherAnswerRepository
    ) {}

    // --- Personal Awards ---

    #[Route('/award/freeze', name: 'admin_award_freeze', methods: ['PUT'])]
    public function freezeAward(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->personalAwardsRepository, $dto->idBag, 'freeze');
    }

    #[Route('/award/active', name: 'admin_award_active', methods: ['PUT'])]
    public function activeAward(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->personalAwardsRepository, $dto->idBag, 'active');
    }

    // --- Research Activities ---

    #[Route('/research/freeze', name: 'admin_research_freeze', methods: ['PUT'])]
    public function freezeResearch(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->researchRepository, $dto->idBag, 'freeze');
    }

    #[Route('/research/active', name: 'admin_research_active', methods: ['PUT'])]
    public function activeResearch(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->researchRepository, $dto->idBag, 'active');
    }

    // --- Innovative Education ---

    #[Route('/innovative/freeze', name: 'admin_innovative_freeze', methods: ['PUT'])]
    public function freezeInnovative(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->innovativeRepository, $dto->idBag, 'freeze');
    }

    #[Route('/innovative/active', name: 'admin_innovative_active', methods: ['PUT'])]
    public function activeInnovative(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->innovativeRepository, $dto->idBag, 'active');
    }

    // --- Social Activities ---

    #[Route('/social/freeze', name: 'admin_social_freeze', methods: ['PUT'])]
    public function freezeSocial(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->socialRepository, $dto->idBag, 'freeze');
    }

    #[Route('/social/active', name: 'admin_social_active', methods: ['PUT'])]
    public function activeSocial(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        return $this->updateStatuses($this->socialRepository, $dto->idBag, 'active');
    }

    // --- Generic Stage Action (for TeacherAnswer) ---

    #[Route('/{stage}/{action}', name: 'admin_generic_award_action', methods: ['PUT'], requirements: ['stage' => '\d+'])]
    public function genericAction(string $stage, string $action, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $item) {
            $id = is_array($item) ? ($item['id'] ?? null) : $item;
            if ($id === null) continue;

            $entity = $this->teacherAnswerRepository->find($id);
            if ($entity) {
                $entity->setActive($action === 'active');
                $this->teacherAnswerRepository->save($entity);
            }
        }
        return $this->json(['message' => 'Success']);
    }

    /**
     * Helper to update statuses for a collection of IDs.
     */
    private function updateStatuses($repository, array $idBag, string $status): JsonResponse
    {
        foreach ($idBag as $item) {
            // Support both scalar ID and ['id' => value] format as seen in some frontend calls
            $id = is_array($item) ? ($item['id'] ?? null) : $item;
            
            if ($id === null) {
                continue;
            }

            $entity = $repository->find($id);
            if ($entity) {
                $entity->setStatus($status);
                $repository->save($entity);
            }
        }

        return $this->json(['message' => 'Success']);
    }
}
