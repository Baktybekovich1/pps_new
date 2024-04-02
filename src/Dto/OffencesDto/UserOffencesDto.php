<?php

namespace App\Dto\OffencesDto;

class UserOffencesDto
{
    public function __construct(
        public int    $id,
        public string $name
    )
    {
    }

}