<?php

namespace App\Controller\user;

use App\Dto\UserEducationsDto;
use App\Entity\InnovativeEducationSubtitle;
use App\Entity\UserInnovativeEducation;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserInnovativeEducationController extends AbstractController
{
    public function __construct(
        private InnovativeEducationListRepository     $innovativeEducationListRepository,
        private InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
        private UserInnovativeEducationRepository     $userInnovativeEducationRepository,
        private UserRepository                        $userRepository)
    {
    }

    #[Route('/education', name: 'app_user_innovative_education')]
    public function index(): JsonResponse
    {
        $educations = $this->innovativeEducationListRepository->findAll();

        return $this->json([
            'educations' => $educations
        ]);
    }

    #[Route('/education/add', name: 'app_user_innovative_education_add',methods: ['POST'])]
    public function add(UserInterface $user,#[MapRequestPayload] UserEducationsDto $dto): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());

        foreach ($dto->educations as $item) {
            $education = new UserInnovativeEducation();
            $education->setUser($user);
            $education->setInnovativeEducationSubtitle($this->innovativeEducationSubtitleRepository->find($item['subId']));
            $education->setLink($item['link']);
            $this->userInnovativeEducationRepository->save($education);
        }


        return $this->json([
            'Красавчик!'

        ]);
    }

}
