<?php
/**
 * Created by max
 * Date: 27/01/16
 * Time: 15:06
 */

namespace Mindy\Cart;

use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;
use Mindy\Cart\Interfaces\ICartItem;
use Mindy\Cart\Interfaces\ICartLine;
use Serializable;

/**
 * Class CartLine
 * @package Mindy
 */
class CartLine implements Serializable, ICartLine
{
    use Configurator, Accessors;

    /**
     * @var bool
     */
    private $_available = true;
    /**
     * @var ICartItem
     */
    private $_product;
    /**
     * @var int|float
     */
    private $_quantity = 1;
    /**
     * @var array
     */
    private $_params = [];
    /**
     * @var []IDiscount
     */
    private $_discounts = [];

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function setParams(array $params = [])
    {
        $this->_params = $params;
        return true;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        $price = $this->getProduct()->getPrice($this->getParams()) * $this->getQuantity();
        foreach ($this->getDiscounts() as $discount) {
            if ($discount instanceof \Closure) {
                list($price, $sumNextDiscount) = $discount->__invoke($this->_product, $price, $this->_params, $this->_quantity);
            } else {
                list($price, $sumNextDiscount) = $discount->applyDiscount($this->_product, $price, $this->_params, $this->_quantity);
            }

            if ($sumNextDiscount === false) {
                break;
            }
        }
        return (float)$price;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setIsAvailable($value)
    {
        $this->_available = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAvailable()
    {
        return $this->_available;
    }

    /**
     * @return \Mindy\Cart\Interfaces\IDiscount[]
     */
    public function getDiscounts()
    {
        return $this->_discounts;
    }

    /**
     * @param \Mindy\Cart\Interfaces\IDiscount[] $discounts
     * @return bool
     */
    public function setDiscounts(array $discounts)
    {
        $this->_discounts = $discounts;
        return true;
    }

    /**
     * @return ICartItem
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param ICartItem $product
     * @return bool
     */
    public function setProduct(ICartItem $product)
    {
        $this->_product = $product;
        return true;
    }

    /**
     * @return int|float
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * @param $quantity int
     * @return bool
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = (int)$quantity;
        return true;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            'quantity' => $this->getQuantity(),
            'product' => $this->getProduct(),
            'params' => $this->getParams()
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        foreach (unserialize($serialized) as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'product' => $this->getProduct()->toArray(),
            'quantity' => $this->getQuantity(),
            'price' => $this->getPrice(),
            'params' => $this->getParams()
        ];
    }
}