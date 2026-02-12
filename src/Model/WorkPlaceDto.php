<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class WorkPlaceDto
{
    #[Assert\NotNull]
    public int $organizationId;

    #[Assert\NotNull]
    public int $instituteId;

    #[Assert\NotNull]
    public bool $regular;
}