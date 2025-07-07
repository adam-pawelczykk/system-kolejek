<?php

namespace App\UI\Controllers;

use App\Application\Command\CreateCoaster;
use App\Application\Command\UpdateCoaster;
use App\Application\DTO\CreateCoasterDTO;
use App\Application\DTO\UpdateCoasterDTO;
use App\Controllers\BaseController;
use App\Domain\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;

/** @author: Adam Pawełczyk */
class CoasterController extends BaseController
{
    public function create(): ResponseInterface
    {
        /** @var CreateCoasterDTO|ResponseInterface $dto */
        $dto = $this->validateDto(CreateCoasterDTO::class);

        if ($dto instanceof ResponseInterface) {
            // If validation failed, return the response directly
            return $dto;
        }

        $commandBus = service('commandBus');
        $commandBus->dispatch($command = new CreateCoaster($dto));

        return $this->response([
            'id' => $command->getCoasterId(),
            'data' => (array) $command->getDto(),
        ]);
    }

    public function update(string $coasterId): ResponseInterface
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterRepository');

        if (!$coaster = $coasterRepository->find($coasterId)) {
            return $this->createNotFoundResponse("Kolejka o ID {$coasterId} nie istnieje");
        }

        /** @var UpdateCoasterDTO|ResponseInterface $dto */
        $dto = $this->validateDto(UpdateCoasterDTO::class);

        if ($dto instanceof ResponseInterface) {
            // If validation failed, return the response directly
            return $dto;
        }

        $commandBus = service('commandBus');
        $commandBus->dispatch(new UpdateCoaster($coasterId, $dto));

        return $this->response([
            'id' => $coasterId,
            'data' => $coaster,
        ]);
    }

    public function view(string $coasterId): ResponseInterface
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterRepository');

        if (!$coaster = $coasterRepository->find($coasterId)) {
            return $this->createNotFoundResponse("Kolejka o ID {$coasterId} nie istnieje");
        }

        return $this->response($coaster);
    }
}
