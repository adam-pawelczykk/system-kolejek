<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Domain\Coaster;
use InvalidArgumentException;

class RemoveWagonHandler
{
    public function __invoke(RemoveWagon $command): void
    {
        $repository = service('coasterRepository');
        /** @var Coaster|null $coaster */
        $coaster = $repository->find($command->getCoasterId());

        if ($coaster === null) {
            throw new InvalidArgumentException('Coaster not found');
        }

        $coaster->removeWagon($command->getWagonId());
        $repository->save($coaster);

    }
}
