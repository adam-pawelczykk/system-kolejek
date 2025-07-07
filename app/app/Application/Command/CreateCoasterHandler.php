<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Domain\Coaster;

class CreateCoasterHandler
{
    public function __invoke(CreateCoaster $command): void
    {
        $repository = service('coasterRepository');
        $repository->save(new Coaster(
            $command->getCoasterId(),
            $command->getDto()->personnel,
            $command->getDto()->clientsPerDay,
            $command->getDto()->trackLength,
            $command->getDto()->hourFrom,
            $command->getDto()->hourTo
        ));
    }
}
