<?php

namespace Mindy\Logger\Handler;

use Monolog\Handler\NullHandler as MonoNullHandler;

/**
 * Class NullHandler
 * @package Mindy\Logger
 */
class NullHandler extends ProxyHandler
{
    public function getHandler()
    {
        return new MonoNullHandler($this->getLevel());
    }
}
