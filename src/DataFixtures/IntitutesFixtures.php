<?php

namespace App\DataFixtures;


use App\Entity\Institutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IntitutesFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
//        $manager->persist((new Institutions())->setName('Институт цифровой трансформации и программирования')
//            ->setUniversity('МУИТ')->setReduction('ИЦТиП')
//        );
//        $manager->persist((new Institutions())->setName('Институт дизайна, архитектуры и текстиля')
//            ->setUniversity('МУИТ')->setReduction('ИДАиТ')
//        );
//        $manager->persist((new Institutions())->setName('Институт строительства и инновационных технологий')
//            ->setUniversity('МУИТ')->setReduction('ИСиИТ')
//        );
//        $manager->persist((new Institutions())->setName('Институт цтп короче из Комтехно')
//            ->setUniversity('Комтехно')->setReduction('ИЦТП')
//        );
//        $manager->flush();

    }
}