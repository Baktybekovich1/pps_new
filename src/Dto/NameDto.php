<?php

namespace App\Dto;

readonly class NameDto
{
    public function __construct(
        public string $name
    )
    {
    }

}