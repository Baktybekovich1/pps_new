<?php

namespace App\Dto\Years;

readonly class GetYearsDto
{
    public function __construct(
        public int    $yearId,
        public string $yearName,
        public array  $yearUserPoints,
    )
    {
    }
}