<?php

namespace App\DataFixtures;

use App\Entity\InnovativeEducationList;
use App\Entity\InnovativeEducationSubtitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InnovativeEducationsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $manager->persist((new InnovativeEducationList())->setName('Учебники, учебные пособия, монография (в соавторстве)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('C грифом МОН КР')
                ->setPoints(40))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Без грифа МОН КР')
                ->setPoints(30))
        );

        $manager->persist((new InnovativeEducationList())->setName('Повышение квалификации (прохождение курсов не менее 48 ч)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('В МУИТ')
                ->setPoints(30))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('В зарубежных вузах и организациях')
                ->setPoints(20))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('В вузах и организациях Кыргызской Республики')
                ->setPoints(15))
        );

        $manager->persist((new InnovativeEducationList())->setName('Чтение дисциплины на иностранном языке (кроме преподавателей иностранных языков)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setPoints(20))
        );

        $manager->persist((new InnovativeEducationList())->setName('Создание электронных учебных материалов (Moodle)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Наличие лекционных и практических материалов, в т.ч. силлабусы, презентации и др.')
                ->setPoints(5))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Наличие тестов по дисциплине')
                ->setPoints(5))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Создание и наличие видеолекций, загруженные в Youtube')
                ->setPoints(15))
        );


        $manager->persist((new InnovativeEducationList())->setName('Разработка методических указаний')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setPoints(10))


        );

        $manager->persist((new InnovativeEducationList())->setName('Рецензирование')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Рецензирование учебников и учебных пособий (кроме методичек)')
                ->setPoints(20))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Рецензирование научных статей')
                ->setPoints(10))
        );


        $manager->persist((new InnovativeEducationList())->setName('Руководство работой студентов, занявших призовые места на конкурсах (выставках)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('На международном')
                ->setPoints(20))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('На республиканском')
                ->setPoints(15))
        );

        $manager->persist((new InnovativeEducationList())->setName('Участие со своими работами на выставках (конкурсах)')
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('На международной')
                ->setPoints(20))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('На республиканской')
                ->setPoints(15))
            ->addInnovativeEducationSubtitle((
            new InnovativeEducationSubtitle())
                ->setName('Победитель, занявший призовые места со своими работами')
                ->setPoints(30))
        );

        $manager->flush();
    }
}