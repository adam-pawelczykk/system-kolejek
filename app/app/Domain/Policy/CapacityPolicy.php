<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\Policy;

use App\Domain\Coaster;
use App\Domain\ValueObject\CapacityStatus;
use App\Domain\Wagon;

class CapacityPolicy
{
    // The time in minutes that a wagon needs to cool down after a run
    public const COOLDOWN_TIME_MINUTES = 5;
    // To calculate speed in minutes, we need to convert seconds to minutes
    public const SECONDS_IN_MINUTE = 60;

    public function evaluate(Coaster $coaster): CapacityStatus
    {
        $wagons = $coaster->getWagons();

        if (empty($wagons)) {
            return new CapacityStatus(
                expectedClients: $coaster->getClientsPerDay(),
                maxPossibleClients: 0,
                missingClients: $coaster->getClientsPerDay(),
                excessClients: 0,
                sufficient: false
            );
        }

        $operatingMinutes = $this->calculateOperatingMinutes($coaster);
        $totalCapacity = 0;

        foreach ($wagons as $wagon) {
            $travelTime = $this->calculateTravelTime($coaster, $wagon);
            $cycleTime = $travelTime + self::COOLDOWN_TIME_MINUTES;

            if ($cycleTime === 0) {
                continue;
            }

            $possibleRuns = floor($operatingMinutes / $cycleTime);
            $totalCapacity += $possibleRuns * $wagon->getNumberOfSeats();
        }

        $expected = $coaster->getClientsPerDay();
        $diff = $totalCapacity - $expected;

        return new CapacityStatus(
            expectedClients: $expected,
            maxPossibleClients: $totalCapacity,
            missingClients: $diff < 0 ? abs($diff) : 0,
            excessClients: $diff > 0 ? $diff : 0,
            sufficient: $diff >= 0
        );
    }

    private function calculateOperatingMinutes(Coaster $coaster): int
    {
        $from = strtotime($coaster->getHourFrom());
        $to = strtotime($coaster->getHourTo());

        return max(0, ($to - $from) / self::SECONDS_IN_MINUTE);
    }

    private function calculateTravelTime(Coaster $coaster, Wagon $wagon): int
    {
        if ($wagon->getWagonSpeed() <= 0) {
            return 0;
        }

        $speedPerMinute = $wagon->getWagonSpeed() * self::SECONDS_IN_MINUTE;

        return ceil($coaster->getTrackLength() / $speedPerMinute);
    }
}
