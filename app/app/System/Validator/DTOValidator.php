<?php
/** @author: Adam Pawełczyk */

namespace App\System\Validator;

use App\System\Exception\ValidationException;
use CodeIgniter\Validation\Validation;

class DTOValidator
{
    public function __construct(protected Validation $validation) {}

    public function validate(array $data, string $dtoClass): ValidatableDTO
    {
        /** @var ValidatableDTO $dtoClass */
        $rules = $dtoClass::rules();

        if (! $this->validation->setRules($rules)->run($data)) {
            throw new ValidationException($this->validation->getErrors());
        }

        return $dtoClass::fromRequest($data);
    }
}
