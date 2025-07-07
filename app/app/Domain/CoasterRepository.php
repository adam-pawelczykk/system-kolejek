<?php

namespace App\Domain;

/** @author: Adam Pawełczyk */
interface CoasterRepository
{
    public function find(string $coasterId): ?Coaster;
    public function delete(string $coasterId): void;
    public function save(Coaster $coaster): void;
}
