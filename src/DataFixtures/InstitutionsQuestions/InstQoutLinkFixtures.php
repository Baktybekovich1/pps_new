<?php

namespace App\DataFixtures\InstitutionsQuestions;

use App\Entity\InstQoutLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstQoutLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager){
        $manager->persist((new InstQoutLink())->setName('Количество образовательных программ бакалавриата')->setPoints(30));
        $manager->persist((new InstQoutLink())->setName('Создание филиала Института в организациях')->setPoints(30));
        $manager->persist((new InstQoutLink())->setName('Стипендиаты учредительской стипендии МУИТ')->setPoints(30));
        $manager->persist((new InstQoutLink())->setName('Количество аудиторий закрепленных за Институтом ')->setPoints(50));
        $manager->persist((new InstQoutLink())->setName('Процент успеваемости студентов (свыше 70%)')->setPoints(30));
        $manager->persist((new InstQoutLink())->setName('Количество выпускников с отличием')->setPoints(30));
        $manager->flush();
    }

}