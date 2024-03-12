<?php

namespace App\DataFixtures;

use App\Entity\PersonalAwards;
use App\Entity\PersonalAwardsSubtitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonalAwardsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new PersonalAwards())->setName('Ученая степень')
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Доктор наук')
                ->setPoints(250))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Кандидат наук')
                ->setPoints(150))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('PhD')
                ->setPoints(100))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Докторант / Аспирант')
                ->setPoints(50))
        );

        $manager->persist((new PersonalAwards())->setName('Ученое звание')
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Академик')
                ->setPoints(250))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Профессор')
                ->setPoints(150))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Профессор МУИТ')
                ->setPoints(100))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Доцент')
                ->setPoints(80))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('И.о. доцента')
                ->setPoints(50))
        );

        $manager->persist((new PersonalAwards())->setName('Гос.награды')
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Лауреат Госпремии Кыргызской Республики в области науки и техники')
                ->setPoints(350))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Заслуженный деятель (по отраслям)')
                ->setPoints(250))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Отличник образования Кыргызской Республики')
                ->setPoints(100))
            ->addPersonalAwardsSubtitle((new PersonalAwardsSubtitle())
                ->setName('Отличник науки Кыргызской Республики')
                ->setPoints(100))
        );
        $manager->flush();
    }
}