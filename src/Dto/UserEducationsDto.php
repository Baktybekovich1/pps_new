<?php

namespace App\Dto;

class UserEducationsDto
{
    public function __construct(
        readonly public array $educations
    )
    {
    }

}