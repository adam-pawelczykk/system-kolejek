<?php

namespace App\Domain;

/** @author: Adam Pawełczyk */
interface CoasterRepository
{
    public function find(string $coasterId): ?Coaster;
    /** @return Coaster[] */
    public function findAll(): array;
    public function delete(string $coasterId): void;
    public function save(Coaster $coaster): void;
}
