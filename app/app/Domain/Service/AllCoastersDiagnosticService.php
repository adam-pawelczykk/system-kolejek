<?php
/** @author: Adam Pawełczyk */

namespace App\Domain\Service;

use App\Domain\CoasterRepository;
use App\Domain\ValueObject\CoasterDiagnosticReport;

readonly class AllCoastersDiagnosticService
{
    public function __construct(
        private CoasterRepository        $coasterRepository,
        private CoasterDiagnosticService $coasterDiagnosticService
    ) {
    }

    /** @return CoasterDiagnosticReport[] */
    public function analyzeAll(): array
    {
        $coasters = $this->coasterRepository->findAll();
        $reports = [];

        foreach ($coasters as $coaster) {
            $reports[] = $this->coasterDiagnosticService->analyze($coaster);
        }

        return $reports;
    }
}
