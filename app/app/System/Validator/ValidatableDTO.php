<?php

namespace App\System\Validator;
/** @author: Adam Pawełczyk */
interface ValidatableDTO
{
    public static function rules(): array;

    public static function fromRequest(array $data): self;
}
