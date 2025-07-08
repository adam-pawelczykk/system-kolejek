<?php
/** @author: Adam Pawełczyk */

namespace App\Domain;

use JsonSerializable;

readonly class Wagon implements JsonSerializable
{
    public function __construct(
        private string $wagonId,
        private int    $numberOfSeats,
        private float  $wagonSpeed
    ) {
    }

    public function getWagonId(): string
    {
        return $this->wagonId;
    }

    public function getNumberOfSeats(): int
    {
        return $this->numberOfSeats;
    }

    public function getWagonSpeed(): float
    {
        return $this->wagonSpeed;
    }

    public function jsonSerialize(): array
    {
        return [
            'wagonId' => $this->wagonId,
            'numberOfSeats' => $this->numberOfSeats,
            'wagonSpeed' => $this->wagonSpeed,
        ];
    }
}
