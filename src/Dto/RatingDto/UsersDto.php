<?php

namespace App\Dto\RatingDto;

class UsersDto
{
    public function __construct(
        public int    $id,
        public string $name
    )
    {
    }

}