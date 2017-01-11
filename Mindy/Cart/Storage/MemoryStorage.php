<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 19/07/16
 * Time: 12:34
 */

namespace Mindy\Cart\Storage;

use Mindy\Cart\CartLine;
use Mindy\Cart\Interfaces\ICartStorage;

class MemoryStorage implements ICartStorage
{
    /**
     * @var array
     */
    private $_data = [];

    /**
     * @param $key
     * @return \Mindy\Cart\CartLine
     */
    public function get($key)
    {
        return $this->_data[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, CartLine $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        unset($this->_data[$key]);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $this->_data = [];
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->_data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     * @return \Mindy\Cart\CartLine[]
     */
    public function getObjects()
    {
        return $this->_data;
    }
}