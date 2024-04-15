<?php

namespace App\Dto\UserAccount;

class AccountFreezedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $stage
    )
    {
    }

}