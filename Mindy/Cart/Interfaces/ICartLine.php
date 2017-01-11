<?php
/**
 * Author: Falaleev Maxim (max107)
 * Email: <max@studio107.ru>
 * Company: Studio107 <http://studio107.ru>
 * Date: 23/05/16 11:18
 */

namespace Mindy\Cart\Interfaces;

interface ICartLine
{
    /**
     * @param \Mindy\Cart\Interfaces\IDiscount[] $discounts
     * @return bool
     */
    public function setDiscounts(array $discounts);
    /**
     * @return \Mindy\Cart\Interfaces\IDiscount[]
     */
    public function getDiscounts();
    /**
     * @return float
     */
    public function getPrice();
    /**
     * @param array $params
     * @return bool
     */
    public function setParams(array $params = []);
    /**
     * @return array
     */
    public function getParams();
    /**
     * @return ICartItem
     */
    public function getProduct();
    /**
     * @param ICartItem $product
     * @return bool
     */
    public function setProduct(ICartItem $product);
    /**
     * @return int
     */
    public function getQuantity();
    /**
     * @param $quantity int
     * @return bool
     */
    public function setQuantity($quantity);
}