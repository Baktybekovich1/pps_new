<?php

namespace App\Dto\UserStagesAdd;

class UserSocialDto
{
    public function __construct(
        readonly public array $socials
    )
    {
    }

}