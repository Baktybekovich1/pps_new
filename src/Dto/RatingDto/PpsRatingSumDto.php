<?php

namespace App\Dto\RatingDto;

use App\Entity\User;

class PpsRatingSumDto
{
    public function __construct(
        public User $user,
        public int  $points,
    )
    {
    }

}