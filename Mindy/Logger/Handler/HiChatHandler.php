<?php

namespace Mindy\Logger\Handler;

use Monolog\Handler\HipChatHandler as MonoHiChatHandler;

/**
 * Class HiChatHandler
 * @package Mindy\Logger
 */
class HiChatHandler extends ProxyHandler
{
    public $token;
    public $room;
    public $notify = false;

    public function getHandler()
    {
        return new MonoHiChatHandler($this->token, $this->room, $this->name, $this->notify, $this->getLevel(), $this->bubble);
    }
}
