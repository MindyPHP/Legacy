<?php
/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 10/12/14 17:50
 */

namespace Mindy\Cart\Interfaces;

/**
 * Interface IDiscount
 * @package Mindy
 *
 * Example:
 * class ExampleDiscount implements IDiscount
 * {
 *      public function applyDiscount(CartLine $item)
 *      {
 *          if (Mindy::app()->user->isGuest === false) {
 *              // Дарим скидку зарегистрированным пользователям
 *              return $item->getPrice() - 200;
 *          } else {
 *              return $item->getPrice();
 *          }
 *      }
 * }
 */
interface IDiscount
{
    /**
     * Apply discount to CartItem position. If new prices is equal old price - return old price.
     * @param ICartItem|ICartLine $product
     * @param $price
     * @param array $params
     * @param $quantity
     * @return float|int new price with discount
     */
    public function applyDiscount(ICartItem $product, $price, array $params = [], $quantity);
}
