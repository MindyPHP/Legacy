<?php


namespace Tests;

use Mindy\Helper\Creator;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->mail = Creator::createObject([
            'class' => '\Mindy\Mail\Mailer',
            'transport' => [
                // 'class' => 'Swift_SmtpTransport',
                'class' => 'Swift_NullTransport'
            ],
        ]);
    }
}
