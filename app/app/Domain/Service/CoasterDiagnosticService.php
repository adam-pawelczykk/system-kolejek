<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\Service;

use App\Domain\Coaster;
use App\Domain\Policy\CapacityPolicy;
use App\Domain\Policy\PersonnelSufficiencyPolicy;
use App\Domain\ValueObject\CoasterDiagnosticReport;

class CoasterDiagnosticService
{
    private PersonnelSufficiencyPolicy $personnelPolicy;
    private CapacityPolicy $capacityPolicy;

    public function __construct() {
        $this->personnelPolicy = new PersonnelSufficiencyPolicy();
        $this->capacityPolicy = new CapacityPolicy();
    }

    public function analyze(Coaster $coaster): CoasterDiagnosticReport
    {
        $personnelStatus = $this->personnelPolicy->evaluate($coaster);
        $capacityStatus = $this->capacityPolicy->evaluate($coaster);

        return new CoasterDiagnosticReport(
            coasterId: $coaster->getId(),
            hourFrom: $coaster->getHourFrom(),
            hourTo: $coaster->getHourTo(),
            personnelStatus: $personnelStatus,
            capacityStatus: $capacityStatus
        );
    }
}
