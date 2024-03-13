<?php

namespace App\DataFixtures;

use App\Entity\Positions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
//        $manager->persist((new Positions())->setName('Директор'));
//        $manager->persist((new Positions())->setName('Профессор'));
//        $manager->persist((new Positions())->setName('Преподователь'));
//        $manager->persist((new Positions())->setName('Стажёр преподователь'));
//        $manager->flush();
    }
}