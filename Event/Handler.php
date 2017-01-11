<?php

namespace Mindy\Event;

use Aura\Signal\Handler as BaseHandler;
use Closure;

/**
 * Class Handler
 * @package Mindy\Event
 */
class Handler extends BaseHandler
{
    /**
     * Execute the handler: if the originating object matches the required
     * sender, and the signal matches the required signal, then run the
     * callback and return the results.
     *
     * @param object $origin The originating object that sent the signal.
     *
     * @param string $signal The signal sent by the originating object.
     *
     * @param array $args Arguments for the callback, which will be invoked
     * if the sender and signal match this Handler.
     *
     * @return null|array An array of parameters suitable for creating a Result
     * object, or null if the origin and signal did not match this Handler.
     */
    public function exec($origin, $signal, array $args)
    {
        // match sender on a specific object, or on a class?
        if (is_object($this->sender)) {
            // match on a specific object
            $match_sender = $this->sender === $origin;
            $callback = $this->callback;
        } else {
            if (
                is_a($origin, $this->sender) &&
                ($this->callback instanceof Closure) === false &&
                $this->callback[1] == $signal
            ) {
                $callback = [$origin, $signal];
            } else {
                $callback = $this->callback;
            }
            // match on a class
            $match_sender = $this->sender == '*' || $origin instanceof $this->sender;
        }

        // match on a signal
        $match_signal = $this->signal == '*' || $this->signal == $signal;

        // do the sender and signal match?
        if ($match_sender && $match_signal) {
            // yes, return an array of params with the callback return value
            return [
                'origin' => $origin,
                'sender' => $this->sender,
                'signal' => $this->signal,
                'value' => call_user_func_array($callback, $args)
            ];
        }
    }
}
