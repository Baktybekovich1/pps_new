<?php

namespace App\Dto\Award;

use Symfony\Component\Security\Core\User\UserInterface;

readonly class SetAwardDto
{
    public function __construct(
        public int    $subtitleId,
        public string $link,
    )
    {
    }

}