<?php
/** @author: Adam Pawełczyk */

namespace App\Application\DTO;

use App\System\Validator\ValidatableDTO;

class WagonDTO implements ValidatableDTO
{
    public function __construct(
        public int     $numberOfSeats,
        public float   $wagonSpeed,
        public ?string $id = null
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            (int)$data['ilosc_miejsc'],
            (float)$data['predkosc_wagonu'],
            $data['id'] ?? null
        );
    }

    public static function rules(): array
    {
        return [
            'ilosc_miejsc' => 'required|is_natural_no_zero',
            'predkosc_wagonu' => 'required|decimal',
            'id' => 'permit_empty|alpha_dash|max_length[36]',
        ];
    }
}
