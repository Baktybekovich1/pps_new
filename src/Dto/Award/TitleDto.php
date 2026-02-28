<?php

namespace App\Dto\Award;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;
class TitleDto
{
    public int $titleId;
    public string $titleName;
    /**
     * @var SubtitleDto[]
     */
    #[Assert\Valid]
    public array $subtitles = [];

}