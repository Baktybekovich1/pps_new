<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new Position())->setName('Директор'));
        $manager->persist((new Position())->setName('Профессор'));
        $manager->persist((new Position())->setName('Преподователь'));
        $manager->persist((new Position())->setName('Стажёр преподователь'));
        $manager->flush();
    }
}