<?php

namespace App\Dto\ResultsOfYears;

class YearDto
{
    public function __construct(
        readonly public string $year
    )
    {
    }

}