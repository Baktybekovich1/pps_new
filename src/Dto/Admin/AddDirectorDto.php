<?php

namespace App\Dto\Admin;

class AddDirectorDto
{
    public function __construct(
        public int $teacherId,
        public int $instituteId,
    )
    {
    }

}