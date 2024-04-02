<?php

namespace App\Dto\UserStagesAdd;

class UserProgressDto
{
    public function __construct(
        readonly public array $awards
    )
    {
    }

}