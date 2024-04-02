<?php

namespace App\Dto\UserStagesAdd;

class UserEducationsDto
{
    public function __construct(
        readonly public array $educations
    )
    {
    }

}