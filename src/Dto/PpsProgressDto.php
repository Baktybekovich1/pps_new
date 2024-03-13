<?php

namespace App\Dto;

class PpsProgressDto
{
    public function __construct(
        readonly public string $name,
        public int $progressPoints

    )
    {

    }

}