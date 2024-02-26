<?php

namespace App\DataFixtures;

use App\Entity\StateAwards;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateAwardsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new StateAwards())->setName('Лауреат Госпремии Кыргызской Республики в области науки и техники')->setPoints(350));
        $manager->persist((new StateAwards())->setName('Заслуженный деятель')->setPoints(250));
        $manager->persist((new StateAwards())->setName('Отличник образования Кыргызской Республики')->setPoints(100));
        $manager->persist((new StateAwards())->setName('Отличник науки Кыргызской Республики')->setPoints(100));
        $manager->flush();
    }
}