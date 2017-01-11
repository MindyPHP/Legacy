<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 19/07/16
 * Time: 13:31
 */

namespace Mindy\Cart\Interfaces;

interface ICartStock
{
    public function isAvailable(ICartItem $product, array $params = [], $quantity = 1);
}