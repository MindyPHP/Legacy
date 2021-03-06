<?php

namespace Mindy\Helper\Interfaces;

/**
 * Arrayable should be implemented by classes that need to be represented in array format.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 * @package Mindy\Helper
 */
interface Arrayable
{
    /**
     * Converts the object into an array.
     * @return array the array representation of this object
     */
    public function toArray();
}
