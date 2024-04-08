<?php

namespace App\Dto\UserAccount;

use phpDocumentor\Reflection\Types\Integer;

class UserResearchGetDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $link
    )
    {
    }

}