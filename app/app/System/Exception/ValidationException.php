<?php

namespace App\System\Exception;

use Exception;

/** @author: Adam Pawełczyk */
class ValidationException extends Exception
{
    public function __construct(public array $errors)
    {
        parent::__construct('Validation failed');
    }
}
