<?php

namespace App\Dto\Expert;

class SetNewExpertDto
{
    public function __construct(
        public int $userId,
        public string $jobTitle
    )
    {
    }

}