<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Application\DTO\WagonDTO;
use Ramsey\Uuid\Uuid;

class AddWagon
{
    public function __construct(
        private readonly string   $coasterId,
        private readonly WagonDTO $dto,
        private ?string           $wagonId = null
    ) {
        if ($this->wagonId === null) {
            $this->wagonId = Uuid::uuid4()->toString();
        }
    }

    public function getWagonId(): string
    {
        return $this->wagonId;
    }

    public function getCoasterId(): string
    {
        return $this->coasterId;
    }

    public function getDto(): WagonDTO
    {
        return $this->dto;
    }
}
