<?php

namespace Mindy\Event;

use Aura\Signal\HandlerFactory as BaseHandlerFactory;

/**
 * Class HandlerFactory
 * @package Mindy\Event
 */
class HandlerFactory extends BaseHandlerFactory
{
    /**
     *
     * Creates and returns a new Handler object.
     *
     * @param array $params An array of key-value pairs corresponding to
     * Handler constructor params.
     *
     * @return Handler
     *
     */
    public function newInstance(array $params)
    {
        $params = array_merge($this->params, $params);
        return new Handler(
            $params['sender'],
            $params['signal'],
            $params['callback']
        );
    }
}
