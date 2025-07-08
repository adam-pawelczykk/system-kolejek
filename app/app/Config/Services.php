<?php

namespace Config;

use App\Domain\Service\CoasterDiagnosticService;
use App\Infrastructure\Repository\RedisCoasterRepository;
use App\System\Bus\CommandBus;
use App\System\Validator\DTOValidator;
use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function validator(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('validator');
        }

        return new DTOValidator(Services::validation());
    }

    public static function commandBus(?bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('commandBus');
        }

        return new CommandBus();
    }

    public static function redisPrefix(): string
    {
        $env = getenv('CI_ENVIRONMENT') ?: 'production';

        return match ($env) {
            'development' => 'dev',
            default       => 'prod',
        };
    }

    public static function redis(?bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('redis');
        }

        $host = getenv('REDIS_HOST') ?: 'redis';
        $port = getenv('REDIS_PORT') ?: 6379;
        $prefix = getenv('REDIS_PREFIX') ?: '';

        return new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port,
            'prefix' => $prefix
        ]);
    }

    public static function coasterRepository($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('coasterRepository');
        }

        return new RedisCoasterRepository(static::redis());
    }

    public static function coasterDiagnostic(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('coasterDiagnostic');
        }

        return new CoasterDiagnosticService();
    }
}
