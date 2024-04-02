<?php

namespace App\Dto\OffencesDto;

class UserOffenceAddDto
{
    public function __construct(
        readonly public array $offence
    )
    {
    }

}