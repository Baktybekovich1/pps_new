<?php

namespace App\Dto\Expert;

class SetPointsToUserDto
{
    public function __construct(
        public int $userId,
        public int $point,
    )
    {
    }

}