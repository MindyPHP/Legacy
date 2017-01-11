<?php
use Mindy\Helper\Interfaces\Arrayable;
use Mindy\Helper\Object;

/**
 * 
 *
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 07/01/14.01.2014 14:54
 */


class ArrayableObject extends Object implements Arrayable
{
    public $data = [1, 2, 3];
}


class ArrayableTest extends TestCase
{
    public function testToArray()
    {
        $obj = new ArrayableObject();
        $this->assertEquals([
            'data' => [1, 2, 3]
        ], $obj->toArray());
    }
}
