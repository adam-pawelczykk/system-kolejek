<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Application\DTO\CreateCoasterDTO;
use Ramsey\Uuid\Uuid;

class CreateCoaster
{
    public function __construct(
        private readonly CreateCoasterDTO $dto,
        private ?string                   $coasterId = null
    ) {
        if ($this->coasterId === null) {
            $this->coasterId = Uuid::uuid4()->toString();
        }
    }

    public function getCoasterId(): string
    {
        return $this->coasterId;
    }

    public function getDto(): CreateCoasterDTO
    {
        return $this->dto;
    }
}
