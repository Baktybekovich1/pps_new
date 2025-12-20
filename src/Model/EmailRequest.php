<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;
class EmailRequest
{
    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;
}