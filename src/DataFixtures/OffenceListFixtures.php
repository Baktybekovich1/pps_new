<?php

namespace App\DataFixtures;

use App\Entity\OffenceList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OffenceListFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

//        $manager->persist((new OffenceList())->setName('Срывы занятий (за каждый срыв)')->setPoints(10));
//        $manager->persist((new OffenceList())->setName('Замена занятий другими преподавателями (за каждое занятие)')->setPoints(5));
//        $manager->persist((new OffenceList())->setName('Опоздание на занятия и мероприятия')->setPoints(5));
//        $manager->flush();
    }
}