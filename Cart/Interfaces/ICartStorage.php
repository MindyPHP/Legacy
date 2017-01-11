<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 02/05/16
 * Time: 18:48
 */

namespace Mindy\Cart\Interfaces;

use Mindy\Cart\CartLine;

interface ICartStorage
{
    /**
     * @param $key
     * @return \Mindy\Cart\CartLine
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, CartLine $value);

    /**
     * @param $key
     * @return bool
     */
    public function remove($key);

    /**
     * @return bool
     */
    public function clear();

    /**
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * @return int
     */
    public function count();

    /**
     * @return \Mindy\Cart\CartLine[]
     */
    public function getObjects();
}