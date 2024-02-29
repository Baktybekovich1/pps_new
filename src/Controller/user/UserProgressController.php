<?php

namespace App\Controller\user;

use App\Dto\UserProgressDto;
use App\Entity\AwardsAndLink;
use App\Entity\UserProgress;
use App\Repository\AcademicDegreeRepository;
use App\Repository\AcademicRankRepository;
use App\Repository\StateAwardsRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProgressController extends AbstractController
{
    public function __construct(
        private AcademicDegreeRepository $degreeRepository,
        private AcademicRankRepository   $rankRepository,
        private StateAwardsRepository    $stateAwardsRepository,
        private UserInfoRepository       $userInfoRepository
    )
    {
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function index(): JsonResponse
    {
        return $this->json([
            'degree' => $this->degreeRepository->findAll(),
            'rank' => $this->rankRepository->findAll(),
            'state_awards' => $this->stateAwardsRepository->findAll()
        ]);
    }

    #[Route('/progress/add', name: 'app_user_progress_add')]
    public function prog_add(#[MapRequestPayload] UserProgressDto $dto, UserInterface $user, UserProgressRepository $userProgressRepository): JsonResponse
    {
        $id = $user->getUserIdentifier();
        $degree = $this->degreeRepository->find($dto->degree);
        $rank = $this->rankRepository->find($dto->rank);
        $userProgress = new UserProgress();
        $userProgress->setUserId($id);
        $userProgress->setDegree($degree->getName());
        $userProgress->setRank($rank->getName());
        $userInfo = $this->userInfoRepository->findOneBy(['userId'=>$id]);
        $points = $degree->getPoints() + $rank->getPoints();



        foreach ($dto->awards as $i) {
            $awards = new AwardsAndLink();
            $award = $this->stateAwardsRepository->find($i['id']);
            $points = $points + $award->getPoints();
            $awards->setName($award->getName());
            $awards->setLink($i['link']);
            $userProgress->addStateAward($awards);
        }
        $userInfo->setTotal($points);
        $this->userInfoRepository->save($userInfo);

        $userProgressRepository->save($userProgress);


        return $this->json([
            'Всё ОКЕЙ!'
        ]);
    }


}
