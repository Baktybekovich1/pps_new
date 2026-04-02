<?php

namespace App\Dto\Director;

class DirectorInstituteDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $organizationName,
        public ?int $teacherTotal,
        public array $teachers
    ) {}
}
