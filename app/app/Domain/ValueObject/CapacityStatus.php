<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\ValueObject;

use JsonSerializable;

readonly class CapacityStatus implements JsonSerializable
{
    public function __construct(
        public int  $expectedClients,
        public int  $maxPossibleClients,
        public int  $missingClients,
        public int  $excessClients,
        public bool $sufficient
    ) {
    }

    public function isProblem(): bool
    {
        return !$this->sufficient;
    }

    public function summary(): string
    {
        if ($this->sufficient && $this->excessClients === 0) {
            return "Status: OK ({$this->maxPossibleClients}/{$this->expectedClients})";
        }

        if (!$this->sufficient) {
            return "Brakuje zdolności do obsługi {$this->missingClients} klientów ({$this->maxPossibleClients}/{$this->expectedClients})";
        }

        return "Nadmierna zdolność: +{$this->excessClients} klientów ({$this->maxPossibleClients}/{$this->expectedClients})";
    }

    public function jsonSerialize(): array
    {
        return [
            'expectedClients' => $this->expectedClients,
            'maxPossibleClients' => $this->maxPossibleClients,
            'missingClients' => $this->missingClients,
            'excessClients' => $this->excessClients,
            'sufficient' => $this->sufficient,
            'summary' => $this->summary(),
        ];
    }
}
