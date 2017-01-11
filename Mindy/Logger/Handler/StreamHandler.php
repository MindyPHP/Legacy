<?php

namespace Mindy\Logger\Handler;

use Mindy\Helper\Alias;
use Monolog\Handler\StreamHandler as MonoStreamHandler;

/**
 * Class StreamHandler
 * @package Mindy\Logger
 */
class StreamHandler extends ProxyHandler
{
    /**
     * @var string path to file or proxy to stdout: php://stdout
     */
    public $stream;

    public $alias = 'application.runtime.application';

    public $filePermission;

    public function init()
    {
        $this->stream = Alias::get($this->alias) . '.log';
        parent::init();
    }

    public function getHandler()
    {
        return new MonoStreamHandler($this->stream, $this->getLevel(), $this->bubble, $this->filePermission);
    }
}

