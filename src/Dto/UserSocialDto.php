<?php

namespace App\Dto;

class UserSocialDto
{
    public function __construct(
        readonly public array $socials
    )
    {
    }

}