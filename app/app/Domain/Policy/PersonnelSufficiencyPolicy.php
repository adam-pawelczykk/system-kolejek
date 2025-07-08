<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\Policy;

use App\Domain\Coaster;
use App\Domain\ValueObject\PersonnelStatus;

class PersonnelSufficiencyPolicy
{
    // How many personnel are required per coaster
    public const PERSONNEL_PER_COASTER = 1;
    // How many personnel are required per wagon
    public const PERSONNEL_PER_WAGON = 2;

    public function evaluate(Coaster $coaster): PersonnelStatus
    {
        $wagonCount = count($coaster->getWagons());
        $requiredPersonnel = self::PERSONNEL_PER_COASTER
            + ($wagonCount * self::PERSONNEL_PER_WAGON);

        $availablePersonnel = $coaster->getPersonnel();
        $diff = $availablePersonnel - $requiredPersonnel;

        return new PersonnelStatus(
            required: $requiredPersonnel,
            available: $availablePersonnel,
            missing: $diff < 0 ? abs($diff) : 0,
            excess: $diff > 0 ? $diff : 0,
            sufficient: $diff >= 0
        );
    }
}
