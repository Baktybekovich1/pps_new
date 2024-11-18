<?php

namespace App\DataFixtures\InstitutionsQuestions;


use App\Entity\InstQoutLinkSubtitle;
use App\Entity\InstQoutLinkTitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstQoutLinkTitleFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
//        $manager->persist((new InstQoutLinkTitle())->setName('Контингент студентов бакалавриата в очной форме')
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('до 100 студентов')->setPoints(50))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('101-200')->setPoints(100))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('201-300')->setPoints(150))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('от 301 и выше')->setPoints(200))
//        );
//
//        $manager->persist((new instQoutLinkTitle())->setName('Контингент студентов магистратуры')
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('до 20 студентов')->setPoints(30))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('от 21 и выше')->setPoints(50))
//        );
//
//        $manager->persist((new instQoutLinkTitle())->setName('Контингент аспирантов')
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('до 10 аспирантов')->setPoints(30))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('от 11 и выше')->setPoints(50))
//        );
//        $manager->persist((new instQoutLinkTitle())->setName('Контингент обучающихся PhD')
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('до 20 обучающихся')->setPoints(30))
//            ->setInstQoutLinkSubtitle((new InstQoutLinkSubtitle())->setName('от 21 и выше')->setPoints(50))
//        );
//
//        $manager->flush();

    }
}