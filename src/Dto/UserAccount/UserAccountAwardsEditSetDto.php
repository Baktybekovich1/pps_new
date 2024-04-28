<?php

namespace App\Dto\UserAccount;

class UserAccountAwardsEditSetDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $link,
        public readonly string $stage
    )
    {
    }

}