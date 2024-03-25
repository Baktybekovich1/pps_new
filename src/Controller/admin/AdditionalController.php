<?php

namespace App\Controller\admin;

use App\Dto\AdditionalDto;
use App\Entity\UserResearchActivitiesList;
use App\Repository\OffenceListRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdditionalController extends AbstractController
{
    public function __construct(
        private ResearchActivitiesListRepository     $researchActivitiesListRepository,
        private OffenceListRepository                $offenceListRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserRepository                       $userRepository,
        private ResearchActivitiesSubtitleRepository $researchActivitiesSubtitleRepository,
        private UserInfoRepository                   $userInfoRepository
    )
    {
    }

    #[Route('/additional', name: 'app_additional')]
    public function index(): JsonResponse
    {

        $scopus = $this->researchActivitiesListRepository->findOneBy(['name' => 'Публикации в базах Scopus и/или Web of Science']);
        $hyrsh = $this->researchActivitiesListRepository->findOneBy(['name' => 'Индекс Хирша по публикациям']);
        $offence = $this->offenceListRepository->findAll();
        return $this->json([
            'scopus' => $scopus,
            'hyrsh' => $hyrsh,
            'offence' => $offence
        ]);
    }

    #[Route('/additional/add', name: 'app_additional_add')]
    public function add(UserInterface $user, #[MapRequestPayload] AdditionalDto $dto): JsonResponse
    {
//        $user = $this->userRepository->find($user->getUserIdentifier());
//        foreach ($dto->awards as $item) {
//            $ural = new UserResearchActivitiesList();
//            $ural->setUser($user);
//            $ural->setSubtitle($this->researchActivitiesSubtitleRepository->find($item['subId']));
//            $ural->setLink($item['link']);
//            $this->userResearchActivitiesListRepository->save($ural);
//        }
//        foreach ($dto->offence as $off) {
//            $userInfo = $this->userInfoRepository->find($user->getId());
//            $offence = $this->offenceListRepository->find($off['id']);
//            $userInfo->setOffenceList($offence);
//            $this->userInfoRepository->save($userInfo);
//        }
//
//        return $this->json(['Success~']);

//        Неправильно!!!
    }
}
