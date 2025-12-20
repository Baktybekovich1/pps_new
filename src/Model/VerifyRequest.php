<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;
class VerifyRequest
{
    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Length(exactly: 6)]
    public string $code;

    #[Assert\Length(min: 6)]
    public string $password;
}