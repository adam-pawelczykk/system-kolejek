<?php
namespace App\Application\DTO;

use App\System\Validator\ValidatableDTO;

/** @author: Adam Pawełczyk */

class CreateCoasterDTO implements ValidatableDTO
{
    public function __construct(
        public int    $personnel,
        public int    $clientsPerDay,
        public int    $trackLength,
        public string $hourFrom,
        public string $hourTo
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            (int)$data['liczba_personelu'],
            (int)$data['liczba_klientow'],
            (int)$data['dl_trasy'],
            $data['godziny_od'],
            $data['godziny_do']
        );
    }

    public static function rules(): array
    {
        return [
            'liczba_personelu' => 'required|is_natural_no_zero',
            'liczba_klientow' => 'required|is_natural_no_zero',
            'dl_trasy' => 'required|is_natural_no_zero',
            'godziny_od' => 'required|regex_match[/^\d{2}:\d{2}$/]',
            'godziny_do' => 'required|regex_match[/^\d{2}:\d{2}$/]',
        ];
    }
}
