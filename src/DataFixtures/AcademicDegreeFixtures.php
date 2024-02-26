<?php

namespace App\DataFixtures;

use App\Entity\AcademicDegree;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AcademicDegreeFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new AcademicDegree())->setName('Доктор наук')->setPoints(250));
        $manager->persist((new AcademicDegree())->setName('Кандидат наук')->setPoints(150));
        $manager->persist((new AcademicDegree())->setName('PhD')->setPoints(100));
        $manager->persist((new AcademicDegree())->setName('Докторант/Аспирант')->setPoints(50));
        $manager->flush();
    }
}