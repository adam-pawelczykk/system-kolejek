<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Domain\Coaster;
use InvalidArgumentException;

class UpdateCoasterHandler
{
    public function __invoke(UpdateCoaster $command): void
    {
        $repository = service('coasterRepository');
        /** @var Coaster|null $coaster */
        $coaster = $repository->find($command->getCoasterId());

        if ($coaster === null) {
            throw new InvalidArgumentException('Coaster not found');
        }

        $coaster->setPersonnel($command->getDto()->personnel);
        $coaster->setClientsPerDay($command->getDto()->clientsPerDay);
        $coaster->setHourFrom($command->getDto()->hourFrom);
        $coaster->setHourTo($command->getDto()->hourTo);

        $repository->save($coaster);
    }
}
