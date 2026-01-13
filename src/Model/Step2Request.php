<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Step2Request
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $lastName;

    #[Assert\Length(max: 255)]
    public ?string $middleName = null;

    public ?bool $hasTrousers = null;

    #[Assert\NotNull]
    public int $positionId;

    /**
     * @var WorkPlaceDto[]
     */
    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'Укажите хотя бы одно место работы')]
    public array $workplaces = [];
}