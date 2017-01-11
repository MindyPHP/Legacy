<?php
/**
 * Created by max
 * Date: 13/01/16
 * Time: 12:08
 */

namespace Mindy\Cart\Traits;

use Mindy\Base\Mindy;

trait CartTrait
{
    /**
     * @param string $moduleName
     * @param string $component
     * @return \Mindy\Cart\Cart
     */
    public function getCart($moduleName = 'Cart', $component = 'cart')
    {
        return Mindy::app()->getModule($moduleName)->getComponent($component);
    }
}
