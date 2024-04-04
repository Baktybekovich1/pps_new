<?php

namespace App\Dto\UserAccount;

class UserAwardsGetDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $link
    )
    {
    }

}