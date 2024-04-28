<?php

namespace App\Dto\AdminStagesEdit;

class StagesEditSubtitleAddDto
{
    public function __construct(
        public ?int    $id,
        public int    $titleId,
        public string $name,
        public int    $points
    )
    {
    }

}