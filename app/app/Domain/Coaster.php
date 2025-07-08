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
        private readonly string $trackLength,
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

    public function setPersonnel(string $personnel): void
    {
        $this->personnel = $personnel;
    }

    public function getClientsPerDay(): string
    {
        return $this->clientsPerDay;
    }

    public function setClientsPerDay(string $clientsPerDay): void
    {
        $this->clientsPerDay = $clientsPerDay;
    }

    public function getTrackLength(): string
    {
        return $this->trackLength;
    }

    public function getHourFrom(): string
    {
        return $this->hourFrom;
    }

    public function setHourFrom(string $hourFrom): void
    {
        $this->hourFrom = $hourFrom;
    }

    public function getHourTo(): string
    {
        return $this->hourTo;
    }

    public function setHourTo(string $hourTo): void
    {
        $this->hourTo = $hourTo;
    }

    public function addWagon(Wagon $wagon): void
    {
        $this->wagons[] = $wagon;
    }

    /**
     * @return Wagon[]
     */
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
