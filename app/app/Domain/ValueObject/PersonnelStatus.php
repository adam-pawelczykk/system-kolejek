<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\ValueObject;

use JsonSerializable;

readonly class PersonnelStatus implements JsonSerializable
{
    public function __construct(
        // How many personnel are required
        public int  $required,
        // How many personnel are available
        public int  $available,
        // How many personnel are missing
        public int  $missing,
        // How many personnel are in excess
        public int  $excess,
        // Is the personnel count sufficient?
        public bool $sufficient
    ) {
    }

    public function isProblem(): bool
    {
        return !$this->sufficient;
    }

    public function summary(): string
    {
        if ($this->sufficient) {
            return "Status: OK ({$this->available}/{$this->required})";
        }

        return "Brakuje {$this->missing} pracowników ({$this->available}/{$this->required})";
    }

    public function jsonSerialize(): array
    {
        return [
            'required' => $this->required,
            'available' => $this->available,
            'missing' => $this->missing,
            'excess' => $this->excess,
            'sufficient' => $this->sufficient,
            'summary' => $this->summary(),
        ];
    }
}
