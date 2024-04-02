<?php

namespace App\Dto\OffencesDto;

class UserOffenceGetDto
{
    public function __construct(
        public int $userId,
        public string $userName,
        public array $offences
    )
    {
        
    }

}