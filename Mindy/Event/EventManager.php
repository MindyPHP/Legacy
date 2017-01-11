<?php

namespace Mindy\Event;

use Aura\Signal\Manager as AuraSignalManager;
use Aura\Signal\ResultCollection;
use Aura\Signal\ResultFactory;

/**
 * Class EventManager
 * @package Mindy\Event
 */
class EventManager extends AuraSignalManager
{
    public function __construct()
    {
        $tmp = func_get_args();
        $args = isset($tmp[0]) ? $tmp[0] : null;
        $events = isset($args['events']) ? $args['events'] : null;
        if (is_string($events)) {
            $handlers = require_once($events);
        } else {
            $handlers = [];
        }
        parent::__construct(new HandlerFactory, new ResultFactory, new ResultCollection, $handlers);
    }
}

