<?php

namespace App\Dto\Award;
use Symfony\Component\Validator\Constraints as Assert;
class GetStageDto
{
    public int $stageId;
    public string $stageName;
    /**
     * @var TitleDto[]
     */
    #[Assert\Valid]
    public array $titles = [];
}