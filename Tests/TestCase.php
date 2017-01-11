<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 04/01/15 19:58
 */

namespace Mindy\Tests;

use Mindy\Base\Mindy;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Mindy\Application\Application
     */
    public $app;

    protected function setUp()
    {
        parent::setUp();
        $this->app = Mindy::app();
    }

    /**
     * Тестирование контроллера. Пример использования:
     *
     * >> $module = Mindy::app()->getModule('Pages');
     * >> $c = new PageController('page', $module);
     * >> $url = '/123';
     * >> $this->assertAction($c, function($c) use ($url) {
     * >>     $c->actionView($url);
     * >> }, '<h1>123</h1>');
     *
     * @param $controller
     * @param $callback
     * @param $result
     * @param bool $trim
     */
    public function assertAction($controller, $callback, $result, $trim = true)
    {
        ob_start();
        $callback->__invoke($controller);
        $this->assertEquals($result, $trim ? trim(ob_get_clean()) : ob_get_clean());
    }
}