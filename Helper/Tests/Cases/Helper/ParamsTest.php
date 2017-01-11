<?php

use Mindy\Helper\Params;

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 07/01/14.01.2014 13:50
 */
class ParamsTest extends TestCase
{
    public function testParams()
    {
        Params::setParams([1, 2, 3]);
        $this->assertEquals([1, 2, 3], Params::getParams());

        Params::setParams([
            'a' => [
                'b' => 1
            ]
        ]);
        $this->assertEquals(1, Params::get('a.b'));
        $this->assertEquals(1, Params::get('a.c', 1));
        $this->assertEquals(1, Params::get('a.b.c', 1));
        $this->assertEquals(null, Params::get('abc'));
    }
}
