<?php

namespace App\Controller\user;

use App\Dto\UserResearchActivitiesAddDto;
use App\Entity\ResearchActivitiesList;
use App\Entity\ResearchActivitiesSubtitle;
use App\Entity\UserResearchActivities;
use App\Entity\UserResearchActivitiesAndLink;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\UserResearchActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserResearchController extends AbstractController
{
    public function __construct(
        private ResearchActivitiesListRepository $activitiesListRepository,
        private ResearchActivitiesSubtitleRepository $activitiesSubtitleRepository,
        private UserResearchActivitiesRepository $userResearchActivitiesRepository
    )
    {
    }

    #[Route('/research', name: 'app_user_research')]
    public function index(): JsonResponse
    {
        $one = $this->activitiesListRepository->find(12);
        $two = $this->activitiesListRepository->find(13);
        $three = $this->activitiesListRepository->find(14);
        $four = $this->activitiesListRepository->find(15);
        $five = $this->activitiesListRepository->find(16);
        $six = $this->activitiesListRepository->find(17);
        $seven = $this->activitiesListRepository->find(18);

        return $this->json([
            'one' => $one,
            'two' => $two,
            'three' => $three,
            'four' => $four,
            'five' => $five,
            'six' => $six,
            'seven' => $seven
        ]);
    }
    #[Route('/research/add',name: 'app_user_research_add',methods: ['POST'])]
    public function research_add(UserInterface $user,#[MapRequestPayload] UserResearchActivitiesAddDto $dto):JsonResponse
    {
        foreach ($dto->list as $activities){
            $activity = new UserResearchActivities();
            $activi = $this->activitiesListRepository->find($activities['id']);
            $activity->setUserId($user->getUserIdentifier());
            $activity->setName($activi->getName());
            $points = 0;
            foreach ($activities['sub'] as $sub) {
                $activitySubtitle = new UserResearchActivitiesAndLink();
                $activitySub = $this->activitiesSubtitleRepository->find($sub['subId']);
                $activitySubtitle->setName($activitySub->getName());
                $activitySubtitle->setLink($sub['subLink']);
                $points = $points + $activitySub->getPoints();
                $activity->addUserResearchActivitiesAndLink($activitySubtitle);
            }
            $activity->setPoints($points);
            $this->userResearchActivitiesRepository->save($activity);
        }





        return $this->json([
            ''
        ]);

    }
}
