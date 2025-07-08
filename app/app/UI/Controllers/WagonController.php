<?php
/** @author: Adam Pawełczyk */

namespace App\UI\Controllers;

use App\Application\Command\AddWagon;
use App\Application\Command\RemoveWagon;
use App\Application\DTO\WagonDTO;
use App\Controllers\BaseController;
use App\Domain\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;

class WagonController extends BaseController
{
    public function add(string $coasterId): ResponseInterface
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterRepository');

        if (!$coaster = $coasterRepository->find($coasterId)) {
            return $this->createNotFoundResponse("Kolejka o ID {$coasterId} nie istnieje");
        }

        /** @var WagonDTO|ResponseInterface $dto */
        $dto = $this->validateDto(WagonDTO::class);

        if ($dto instanceof ResponseInterface) {
            // If validation failed, return the response directly
            return $dto;
        }

        $command = new AddWagon($coaster->getId(), $dto, $dto->id);

        $commandBus = service('commandBus');
        $commandBus->dispatch($command);

        return $this->response([
            'id' => $command->getWagonId(),
            'data' => (array) $command->getDto()
        ]);
    }

    public function remove(string $coasterId, string $wagonId): ResponseInterface
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterRepository');

        if (!$coasterRepository->find($coasterId)) {
            return $this->createNotFoundResponse("Kolejka o ID {$coasterId} nie istnieje");
        }

        $commandBus = service('commandBus');
        $commandBus->dispatch(new RemoveWagon($coasterId, $wagonId));

        return $this->response([
            'message' => "Wagon o ID {$wagonId} został usunięty z kolejki o ID {$coasterId}"
        ]);
    }
}
