<?php

namespace Mindy\Logger\Handler;

use Mindy\Base\Mindy;
use Monolog\Handler\SwiftMailerHandler as MonoSwiftMailerHandler;

/**
 * Class SwiftMailerHandler
 * @package Mindy\Logger
 */
class SwiftMailerHandler extends ProxyHandler
{
    public function getHandler()
    {
        $mail = Mindy::app()->mail;
        $mailer = $mail->getSwiftMailer();
        $message = $mail->compose()->getSwiftMessage();
        return new MonoSwiftMailerHandler($mailer, $message, $this->getLevel(), $this->bubble);
    }
}
