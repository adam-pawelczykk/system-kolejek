<?php
/** @author: Adam Pawełczyk */

namespace App\Application\DTO;

use App\System\Validator\ValidatableDTO;

class UpdateCoasterDTO implements ValidatableDTO
{
    public function __construct(
        public int    $personnel,
        public int    $clientsPerDay,
        public string $hourFrom,
        public string $hourTo
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            (int) $data['liczba_personelu'],
            (int) $data['liczba_klientow'],
            $data['godziny_od'],
            $data['godziny_do'],
        );
    }

    public static function rules(): array
    {
        return [
            'liczba_personelu' => 'required|is_natural_no_zero',
            'liczba_klientow' => 'required|is_natural_no_zero',
            'godziny_od' => 'required|regex_match[/^\d{2}:\d{2}$/]',
            'godziny_do' => 'required|regex_match[/^\d{2}:\d{2}$/]',
        ];
    }
}
