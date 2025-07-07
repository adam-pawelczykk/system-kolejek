<?php

namespace App\Domain;

use JsonSerializable;

/** @author: Adam Pawełczyk */
class Coaster implements JsonSerializable
{
    private array $wagons;

    public function __construct(
        private readonly string $id,
        private string          $personnel,
        private string          $clientsPerDay,
        private string          $trackLength,
        private string          $hourFrom,
        private string          $hourTo,
        array                   $wagons = []
    ) {
        $this->wagons = $wagons;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPersonnel(): string
    {
        return $this->personnel;
    }

    public function getClientsPerDay(): string
    {
        return $this->clientsPerDay;
    }

    public function getTrackLength(): string
    {
        return $this->trackLength;
    }

    public function getHourFrom(): string
    {
        return $this->hourFrom;
    }

    public function getHourTo(): string
    {
        return $this->hourTo;
    }

    public function addWagon(Wagon $wagon): void
    {
        $this->wagons[] = $wagon;
    }

    public function getWagons(): array
    {
        return $this->wagons;
    }

    public function removeWagon(string $wagonId): void
    {
        foreach ($this->wagons as $key => $wagon) {
            if ($wagon->getWagonId() === $wagonId) {
                unset($this->wagons[$key]);
                return;
            }
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'personnel' => $this->personnel,
            'clientsPerDay' => $this->clientsPerDay,
            'trackLength' => $this->trackLength,
            'hourFrom' => $this->hourFrom,
            'hourTo' => $this->hourTo,
            'wagons' => $this->wagons
        ];
    }
}
