<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Application\DTO\UpdateCoasterDTO;

readonly class UpdateCoaster
{
    public function __construct(
        private string           $coasterId,
        private UpdateCoasterDTO $dto,
    ) {
    }

    public function getCoasterId(): string
    {
        return $this->coasterId;
    }

    public function getDto(): UpdateCoasterDTO
    {
        return $this->dto;
    }
}
