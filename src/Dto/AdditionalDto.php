<?php

namespace App\Dto;

class AdditionalDto
{
    public function __construct(
        readonly public array $awards,
        readonly public array $offence
    )
    {
    }

}