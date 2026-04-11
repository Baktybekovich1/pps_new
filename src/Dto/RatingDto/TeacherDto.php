<?php

namespace App\Dto\RatingDto;

class TeacherDto
{
    public function __construct(
        public int $id,
        public string $name,
        public array $awards,
        public int $total,
        public int $expertPoints = 0,
    )
    {
    }

}
