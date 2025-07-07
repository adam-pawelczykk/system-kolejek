<?php

namespace App\Infrastructure\Repository;

use App\Domain\Coaster;
use App\Domain\CoasterRepository;
use App\Domain\Wagon;

/** @author: Adam Pawełczyk */
class RedisCoasterRepository implements CoasterRepository
{
    protected $redis;

    public function __construct()
    {
        $this->redis = service('redis');
    }

    public function save(Coaster $coaster): void
    {
        $this->redis->set(
            "coasters:{$coaster->getId()}",
            json_encode($coaster)
        );
    }

    public function find(string $coasterId): ?Coaster
    {
        $raw = $this->redis->get("coasters:$coasterId");

        if (null === $raw) {
            return null;
        }

        $data = json_decode($raw, true);
        $coaster = new Coaster(
            $data['id'],
            $data['personnel'],
            $data['clientsPerDay'],
            $data['clientsPerDay'],
            $data['hourFrom'],
            $data['hourTo']
        );

        foreach ($data['wagons'] ?? [] as $row) {
            $coaster->addWagon(new Wagon(
                $row['wagonId'],
                $row['coasterId'],
                $row['numberOfSeats'],
                $row['wagonSpeed']
            ));
        }

        return $coaster;
    }

    public function delete(string $coasterId): void
    {
        $this->redis->del("coasters:$coasterId");
    }
}
