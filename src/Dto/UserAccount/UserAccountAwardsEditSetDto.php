<?php

namespace App\Dto\UserAccount;

class UserAccountAwardsEditSetDto
{
    public function __construct(
        public readonly array $bag
    )
    {
    }

}