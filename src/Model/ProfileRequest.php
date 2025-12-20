<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;
class ProfileRequest
{
    #[Assert\Length(min: 1)]
    public ?string $firstName = null;
    #[Assert\Length(min: 1)]
    public ?string $lastName = null;
}