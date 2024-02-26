<?php

namespace App\DataFixtures;

use App\Entity\AcademicRank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AcademicRankFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new AcademicRank())->setName('Академик')->setPoints(250));
        $manager->persist((new AcademicRank())->setName('Профессор')->setPoints(150));
        $manager->persist((new AcademicRank())->setName('Профессор МУИТ')->setPoints(100));
        $manager->persist((new AcademicRank())->setName('Доцент')->setPoints(80));
        $manager->flush();
    }
}