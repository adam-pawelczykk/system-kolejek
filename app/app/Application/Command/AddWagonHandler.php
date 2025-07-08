<?php
/** @author: Adam Pawełczyk */

namespace App\Application\Command;

use App\Domain\Coaster;
use App\Domain\Wagon;
use InvalidArgumentException;

class AddWagonHandler
{
    public function __invoke(AddWagon $command): void
    {
        $repository = service('coasterRepository');
        /** @var Coaster|null $coaster */
        $coaster = $repository->find($command->getCoasterId());

        if ($coaster === null) {
            throw new InvalidArgumentException('Coaster not found');
        }

        foreach ($coaster->getWagons() as $wagon) {
            if ($wagon->getWagonId() === $command->getWagonId()) {
                throw new InvalidArgumentException(
                    "Wagon {${$command->getWagonId()}} already exists"
                );
            }
        }

        $wagon = new Wagon(
            $command->getWagonId(),
            $command->getDto()->numberOfSeats,
            $command->getDto()->wagonSpeed
        );

        $coaster->addWagon($wagon);
        $repository->save($coaster);
    }
}
