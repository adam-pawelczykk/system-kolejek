<?php
/** @author: Adam Pawełczyk */

namespace App\Commands;

use App\Domain\CoasterRepository;
use App\Domain\Service\AllCoastersDiagnosticService;
use App\Domain\Service\CoasterDiagnosticService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use React\EventLoop\Loop;

class MonitorCommand extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'monitor';
    protected $description = 'Monitoruje stan kolejek w czasie rzeczywistym co 5s.';

    public function run(array $params): void
    {
        /** @var CoasterDiagnosticService $coasterDiagnosticService */
        $coasterDiagnosticService = Services::coasterDiagnostic();
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = Services::coasterRepository();

        $diagnosticService = new AllCoastersDiagnosticService(
            coasterRepository: $coasterRepository,
            coasterDiagnosticService: $coasterDiagnosticService
        );

        $loop = Loop::get();
        $loop->addPeriodicTimer(5, function () use ($diagnosticService) {
            $reports = $diagnosticService->analyzeAll();

            CLI::write('[Monitor Kolejek] ' . date('H:i:s'), 'green');
            CLI::write(str_repeat('-', 40));

            foreach ($reports as $report) {
                CLI::write($report->summary());

                if ($report->isProblem()) {
                    log_message('error', '[Monitor] Problem z kolejką {0}: {1}', [
                        $report->coasterId,
                        $report->summary()
                    ]);
                }

                CLI::write(str_repeat('-', 40));
            }
        });

        $loop->run();
    }
}
