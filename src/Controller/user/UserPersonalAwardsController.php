<?php

namespace App\Controller\user;

use App\Dto\UserStagesAdd\UserProgressDto;
use App\Entity\UserPersonalAwards;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPersonalAwardsController extends AbstractController
{
    public function __construct(
        private UserRepository                   $userRepository,
        private PersonalAwardsRepository         $personalAwardsRepository,
        private PersonalAwardsSubtitleRepository $personalAwardsSubtitleRepository,
        private UserPersonalAwardsRepository     $userPersonalAwardsRepository
    )
    {
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function index(): JsonResponse
    {
        $personalAwards = $this->personalAwardsRepository->findAll();
        return $this->json([
            $personalAwards
        ]);
    }

    #[Route('/progress/add', name: 'app_user_progress_add')]
    public function prog_add(#[MapRequestPayload] UserProgressDto $dto, UserInterface $user): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());
        foreach ($dto->awards as $item) {
            if ($this->userPersonalAwardsRepository->findBy(['user' => $user, 'subtitle' => $this->personalAwardsSubtitleRepository->find($item['subId'])])) {
                $sub = $this->userPersonalAwardsRepository->findOneBy(['subtitle' => $item['subId']]);
                $award = $this->userPersonalAwardsRepository->find($sub->getId());
            } else {
                $award = new UserPersonalAwards();
                $award->setUser($user);
            }

            $personalAwardSubtitle = $this->personalAwardsSubtitleRepository->find($item['subId']);
            $award->setSubtitle($personalAwardSubtitle);
            $award->setStatus('active');
            if (isset($item['link'])) {
                $award->setLink($item['link']);
            } else {
                $award->setLink("Нет ссылки");
            }
            $this->userPersonalAwardsRepository->save($award);

        }
        return $this->json([
            'Всё ОКЕЙ!'
        ]);
    }


}
