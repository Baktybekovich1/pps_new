<?php

namespace App\Util;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrors
{
    public static function format(ConstraintViolationListInterface $list): array
    {
        $result = [];
        foreach ($list as $item) {
            $result[] = $item;
        }
        return $result;
    }
}