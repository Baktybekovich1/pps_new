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

    #[Route('/progress', name: 'app_user_progress',methods: ['GET'])]
    public function index(): JsonResponse
    {
        $personalAwards = $this->personalAwardsRepository->findAll();
        return $this->json([
            $personalAwards
        ]);
    }

    #[Route('/progress/add', name: 'app_user_progress_add', methods: ['POST'])]
    public function prog_add(#[MapRequestPayload] UserProgressDto $dto, UserInterface $user): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());
        foreach ($dto->awards as $item) {
            $subtitle = $this->personalAwardsSubtitleRepository->find($item['subId']);
            $title = $subtitle->getTitle();
            $award = null;

            if ($title->getId() == 1) {
                $award = $this->issetAward($title, $user);
            } elseif ($title->getId() == 2) {
                $award = $this->issetAward($title, $user);
            } elseif ($title->getId() == 3) {
                $award = $this->issetGosAward($subtitle, $user);
            }
            if ($award == null) {
                $award = new UserPersonalAwards();
            }
            $award->setSubtitle($subtitle);
            $award->setUser($user);
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

    public function issetAward($title, $user)
    {
        $userAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
        foreach ($userAwards as $userAward) {
            if ($userAward->getSubtitle()->getTitle() == $title) {
                return $userAward;
            }
        }
        return null;
    }


    public function issetGosAward($subtitle, $user)
    {
        $userAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
        foreach ($userAwards as $userAward) {
            if ($userAward->getSubtitle() == $subtitle) {
                return $userAward;
            }
        }
        return null;
    }


}
