<?php

namespace App\Dto\Institute;

use App\Entity\Institute;

class InstituteDto
{
    public function __construct(
        public int    $id,
        public string $name,
        public int    $teacherTotal,
    )
    {
    }

}