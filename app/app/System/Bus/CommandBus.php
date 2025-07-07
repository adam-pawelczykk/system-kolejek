<?php
/** @author: Adam Pawełczyk */

namespace App\System\Bus;

use RuntimeException;

class CommandBus
{
    public function dispatch(object $command)
    {
        $handlerClass = get_class($command) . 'Handler';

        if (!class_exists($handlerClass)) {
            throw new RuntimeException("Handler not found for " . get_class($command));
        }

        $handler = new $handlerClass();

        return $handler($command);
    }
}
