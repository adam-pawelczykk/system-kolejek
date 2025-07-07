<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Application\DTO\WagonDTO;
use Ramsey\Uuid\Uuid;

readonly class RemoveWagon
{
    public function __construct(
        private string $coasterId,
        private string $wagonId
    ) {
    }

    public function getCoasterId(): string
    {
        return $this->coasterId;
    }

    public function getWagonId(): string
    {
        return $this->wagonId;
    }
}
