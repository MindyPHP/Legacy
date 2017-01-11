<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 28/10/2014 13:06
 * @date 27/01/2016 16:07
 */

namespace Mindy\Cart;

use Mindy\Cart\Interfaces\ICartStorage;
use Mindy\Cart\Interfaces\IDiscount;
use Mindy\Helper\Creator;
use Mindy\Cart\Interfaces\ICartItem;

class Cart
{
    /**
     * @var string|array component configuration
     */
    public $storage = [
        'class' => '\Mindy\Cart\Storage\SessionStorage'
    ];
    /**
     * @var \Mindy\Cart\Interfaces\IDiscount[]|array|\Closure[]
     */
    public $discounts = [];
    /**
     * @var \Mindy\Cart\Interfaces\ICartStock
     */
    protected $stock;
    /**
     * @var null|\Closure
     */
    protected $fetchCallback;
    /**
     * @var \Mindy\Cart\Interfaces\ICartStorage
     */
    private $_storage;
    /**
     * @var \Mindy\Cart\Interfaces\IDiscount[]
     */
    private $_discounts = null;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return \Mindy\Cart\Interfaces\ICartStorage
     */
    public function getStorage()
    {
        if ($this->_storage === null) {
            if ($this->storage instanceof ICartStorage) {
                $this->_storage = $this->storage;
            } else {
                $this->_storage = Creator::createObject($this->storage);
            }
        }
        return $this->_storage;
    }

    /**
     * @param ICartItem $object
     * @param array $params
     * @return string
     */
    public function makeKey(ICartItem $object, array $params = [])
    {
        return md5(get_class($object) . serialize([$object->getUniqueId(), $params]));
    }

    /**
     * @param $key
     * @param $quantity
     * @return bool
     */
    public function quantity($key, $quantity)
    {
        $cartLine = $this->get($key);
        if ($this->checkInStock($cartLine->getProduct(), $cartLine->getParams(), $quantity) === false) {
            return false;
        }

        $cartLine->setQuantity($quantity);
        return $this->set($key, $cartLine);
    }

    /**
     * @param ICartItem $product
     * @param array $params
     * @param int $quantity
     * @return bool
     */
    protected function checkInStock(ICartItem $product, array $params = [], $quantity = 1)
    {
        if ($this->stock && $this->stock->isAvailable($product, $params, $quantity) === false) {
            return false;
        }
        return true;
    }

    /**
     * @param $key
     * @param CartLine $cartLine
     * @return bool
     */
    public function set($key, CartLine $cartLine)
    {
        if ($this->checkInStock($cartLine->getProduct(), $cartLine->getParams(), $cartLine->getQuantity()) === false) {
            return false;
        }
        return $this->getStorage()->set($key, $cartLine);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->getStorage()->has($key);
    }

    /**
     * @param $key
     * @return CartLine
     */
    public function get($key)
    {
        return $this->getStorage()->get($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        return $this->getStorage()->remove($key);
    }

    /**
     * @DEPRECATED
     * @param $key
     * @return bool
     */
    public function removeKey($key)
    {
        return $this->remove($key);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->getStorage()->clear();
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        $quantity = 0;
        foreach ($this->getObjects() as $line) {
            $quantity += $line->getQuantity();
        }
        return $quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        $total = 0.0;
        foreach ($this->getObjects() as $line) {
            $total += $line->getPrice();
        }
        return (float)$total;
    }

    /**
     * Force update all products in cart
     */
    public function update()
    {
        $fetchCallback = $this->fetchCallback;
        if ($fetchCallback === null) {
            return false;
        }

        $lines = $this->getObjects();
        foreach ($lines as $key => $line) {
            $newProduct = $fetchCallback->__invoke($line->getProduct());
            if ($newProduct) {
                $line->setProduct($newProduct);
            } else {
                $line->setIsAvailable(false);
            }
            $this->set($key, $line);
        }
    }

    /**
     * @return \Mindy\Cart\CartLine[]
     */
    public function getObjects()
    {
        return $this->getStorage()->getObjects();
    }

    /**
     * @return bool
     */
    public function getIsEmpty()
    {
        return $this->getStorage()->count() === 0;
    }

    /**
     * @param ICartItem $product
     * @param array $params
     * @param int $quantity
     * @return string
     */
    public function add(ICartItem $product, array $params = [], $quantity = 1)
    {
        if ($this->checkInStock($product, $params, $quantity) === false) {
            return false;
        }

        $key = $this->makeKey($product, $params);
        if ($this->has($key)) {
            $line = $this->get($key);
            $line->setQuantity($line->getQuantity() + $quantity);
        } else {
            $line = new CartLine([
                'quantity' => $quantity,
                'product' => $product,
                'params' => $params,
                'discounts' => $this->getDiscounts()
            ]);
        }
        $this->set($key, $line);
        return $key;
    }

    /**
     * Create CartLine from ICartItem and return price with discount
     * @param ICartItem $product
     * @param int $quantity
     * @param array $params
     * @return float
     */
    public function applyDiscount(ICartItem $product, array $params = [], $quantity = 1)
    {
        return (new CartLine([
            'quantity' => $quantity,
            'params' => $params,
            'product' => $product,
            'discounts' => $this->getDiscounts()
        ]))->getPrice();
    }

    /**
     * @return \Mindy\Cart\Interfaces\IDiscount[]
     */
    public function getDiscounts()
    {
        if ($this->_discounts === null) {
            $this->_discounts = [];
            foreach ($this->discounts as $className) {
                if ($className instanceof IDiscount || $className instanceof \Closure) {
                    $this->_discounts[] = $className;
                } else {
                    $this->_discounts[] = Creator::createObject($className);
                }
            }
        }

        return $this->_discounts;
    }

    public function countInCart(ICartItem $product, array $params = [])
    {
        $key = $this->makeKey($product, $params);
        return $this->has($key) ? $this->get($key)->getQuantity() : 0;
    }
}
