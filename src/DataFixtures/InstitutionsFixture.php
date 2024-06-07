<?php

namespace App\DataFixtures;

use App\Entity\Institutions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstitutionsFixture extends Fixture
{

    public function load(ObjectManager $manager)
    {
//        $manager->persist((new Institutions())
//            ->setName('Институт цифровой трансформации и программирования')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИЦТП'));
//
//        $manager->persist((new Institutions())
//            ->setName('Институт дизайна, архитектуры и текстиля')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИДАТ'));
//
//        $manager->persist((new Institutions())
//            ->setName('Институт строительства и инновационных технологий')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИСИТ'));
//
//        $manager->persist((new Institutions())
//            ->setName('Институт энергетики и транспорта')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИЭТ'));
//
//        $manager->persist((new Institutions())
//            ->setName('Институт экономики и менеджмента')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИЭМ'));
//
//        $manager->persist((new Institutions())
//            ->setName('Российско-Кыргызский институт автоматизации управления бизнеса')
//            ->setUniversity('МУИТ')
//            ->setReduction('РКИАУБ'));
//
//        $manager->persist((new Institutions())
//            ->setName('Институт межкультурной коммуникации и психологии')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИМКП'));
//
//
//        $manager->persist((new Institutions())
//            ->setName('Институт маркетинга и электронной коммерции')
//            ->setUniversity('МУИТ')
//            ->setReduction('ИМЭК'));
//
        $manager->persist((new Institutions())
            ->setName('Гуманитарных и экономических дисциплин')
            ->setUniversity('КИТЭ')
            ->setReduction('ГЭД'));

        $manager->persist((new Institutions())
            ->setName('Естественных и технических дисциплин')
            ->setUniversity('КИТЭ')
            ->setReduction('ЕТД'));
        $manager->persist((new Institutions())
            ->setName('Информатики вычислительной техники и дизайна')
            ->setUniversity('Комтехно')
            ->setReduction('ИВТД'));
        $manager->persist((new Institutions())
            ->setName('Экономики, бухгалтерского учёта и банковского дела')
            ->setUniversity('Комтехно')
            ->setReduction('ЭБУБД'));


        $manager->flush();


    }
}