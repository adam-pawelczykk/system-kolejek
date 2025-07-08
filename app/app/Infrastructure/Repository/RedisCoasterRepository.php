<?php

namespace App\Infrastructure\Repository;

use App\Domain\Coaster;
use App\Domain\CoasterRepository;
use App\Domain\Wagon;
use Config\Services;
use Predis\Client;

/** @author: Adam Pawełczyk */
class RedisCoasterRepository implements CoasterRepository
{
    private const REDIS_PREFIX = 'coasters:';
    private const KEY_PREFIX = 'coasters:';
    protected Client $redis;
    private string $storePrefix;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
        $this->storePrefix = sprintf('{%s}:%s', Services::redisPrefix(), self::KEY_PREFIX);
    }

    public function save(Coaster $coaster): void
    {
        $this->redis->set(
            $this->storePrefix . ':' . $coaster->getId(),
            json_encode($coaster)
        );
    }

    public function find(string $coasterId): ?Coaster
    {
        $raw = $this->redis->get($this->storePrefix . ':' . $coasterId);

        if (null === $raw) {
            return null;
        }

        return $this->mapToEntity($raw);
    }

    /** @return Coaster[] */
    public function findAll(): array
    {
        $keys = $this->redis->keys($this->storePrefix . ':*');
        $coasters = [];

        foreach ($keys as $key) {
            if (!$raw = $this->redis->get($key)) {
                continue;
            }

            $coasters[] = $this->mapToEntity($raw);
        }

        return $coasters;
    }

    public function delete(string $coasterId): void
    {
        $this->redis->del($this->storePrefix . ':' . $coasterId);
    }

    private function mapToEntity(string $raw): Coaster
    {
        $data = json_decode($raw, true);
        $coaster = new Coaster(
            $data['id'],
            $data['personnel'],
            $data['clientsPerDay'],
            $data['trackLength'],
            $data['hourFrom'],
            $data['hourTo']
        );

        foreach ($data['wagons'] ?? [] as $row) {
            $coaster->addWagon(new Wagon(
                $row['wagonId'],
                $row['numberOfSeats'],
                $row['wagonSpeed']
            ));
        }

        return $coaster;
    }
}
