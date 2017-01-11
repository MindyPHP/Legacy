<?php

namespace Mindy\Cart\Storage;

use Mindy\Cart\CartLine;
use Mindy\Cart\Interfaces\ICartStorage;

/**
 * Class SessionStorage
 * @package Mindy
 */
class SessionStorage implements ICartStorage
{
    const KEY = 'cart';

    /**
     * Prepare array with productions in $_SESSION
     */
    public function init()
    {
        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }
    }

    /**
     * @param $key
     * @return \Mindy\Cart\CartLine
     */
    public function get($key)
    {
        return unserialize($_SESSION[self::KEY][$key]);
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        unset($_SESSION[self::KEY][$key]);
        return true;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, CartLine $value)
    {
        $_SESSION[self::KEY][$key] = serialize($value);
        return true;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($_SESSION[self::KEY]);
    }

    /**
     * @return \Mindy\Cart\CartLine[]
     */
    public function getObjects()
    {
        $objects = [];
        foreach ($_SESSION[self::KEY] as $key => $data) {
            $objects[$key] = unserialize($data);
        }
        return $objects;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_SESSION[self::KEY]);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $_SESSION[self::KEY] = [];
        return true;
    }
}
