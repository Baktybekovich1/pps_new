<?php

namespace App\Exception;

use RuntimeException;

class NotAccessToAwardExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('У вас нет доступа к этой награде!');
    }

}