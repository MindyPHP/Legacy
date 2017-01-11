<?php

namespace Mindy\Logger\Handler;

use Mindy\Base\Mindy;
use Monolog\Handler\NativeMailerHandler as MonoNativeMailerHandler;

/**
 * Class NativeMailerHandler
 * @package Mindy\Logger
 */
class NativeMailerHandler extends ProxyHandler
{
    public $subject = "Logging";

    public $maxColumnWidth = 70;

    public function getHandler()
    {
        $mail = Mindy::app()->mail;
        return new MonoNativeMailerHandler($mail->admins, $this->subject, $mail->defaultFrom, $this->getLevel(), $this->bubble, $this->maxColumnWidth);
    }
}
