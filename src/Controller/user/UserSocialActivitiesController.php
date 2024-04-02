<?php

namespace App\Controller\user;

use App\Dto\UserStagesAdd\UserSocialDto;
use App\Entity\UserSocialActivities;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
use App\Repository\UserRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSocialActivitiesController extends AbstractController
{
    public function __construct(
        private UserSocialActivitiesRepository     $userSocialActivitiesRepository,
        private UserRepository                     $userRepository,
        private SocialActivitiesSubtitleRepository $socialActivitiesSubtitleRepository,
        private SocialActivitiesListRepository     $socialActivitiesListRepository
    )
    {
    }

    #[Route('/social', name: 'app_user_social_activities')]
    public function index(): JsonResponse
    {
        $socials = $this->socialActivitiesListRepository->findAll();

        return $this->json([
            $socials
        ]);
    }

    #[Route('/social/add', name: 'app_user_social_add', methods: ['POST'])]
    public function add(UserInterface $user, #[MapRequestPayload] UserSocialDto $dto): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());

        foreach ($dto->socials as $item) {
            $social = new UserSocialActivities();
            $social->setUser($user);
            $social->setSocialActivitiesSubtitle($this->socialActivitiesSubtitleRepository->find($item['subId']));
            $social->setLink($item['link']);
            $this->userSocialActivitiesRepository->save($social);
        }

        return $this->json([
            'Ваще Красавчик!'
        ]);
    }
}
