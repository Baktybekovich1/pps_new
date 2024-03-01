<?php

namespace App\Dto;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserUsDto
{
    public function __construct(
        readonly public int $id
    )
    {
    }
}