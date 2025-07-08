<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\ValueObject;

use JsonSerializable;

readonly class CoasterDiagnosticReport implements JsonSerializable
{
    public function __construct(
        public string          $coasterId,
        public string          $hourFrom,
        public string          $hourTo,
        public PersonnelStatus $personnelStatus,
        public CapacityStatus  $capacityStatus
    ) {
    }

    public function isProblem(): bool
    {
        return $this->personnelStatus->isProblem() || $this->capacityStatus->isProblem();
    }

    public function summary(): string
    {
        $summary = "[{$this->coasterId}] " . PHP_EOL;
        $summary .= "Godziny działania: {$this->hourFrom} - {$this->hourTo}" . PHP_EOL;
        $summary .= "Personel: {$this->personnelStatus->summary()}" . PHP_EOL;
        $summary .= "Klienci: {$this->capacityStatus->summary()}" . PHP_EOL;

        return $summary;
    }

    public function jsonSerialize(): array
    {
        return [
            'coasterId' => $this->coasterId,
            'hourFrom' => $this->hourFrom,
            'hourTo' => $this->hourTo,
            'personnelStatus' => $this->personnelStatus,
            'capacityStatus' => $this->capacityStatus,
            'summary' => $this->summary(),
        ];
    }
}
