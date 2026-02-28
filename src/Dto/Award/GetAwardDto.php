<?php

namespace App\Dto\Award;

use Symfony\Component\Validator\Constraints as Assert;
class GetAwardDto
{
    /**
     * @var GetStageDto[]
     */
    #[Assert\Valid]
    public array $stages = [];

}