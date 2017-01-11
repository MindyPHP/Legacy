<?php
/**
 * 
 *
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 10/05/14.05.2014 15:40
 */

namespace Mindy\Utils;


class Settings
{
    public static function override(array $original, array $settings)
    {
        foreach($settings as $key => $item) {
            foreach($item as $k => $value) {
                $original[$key][$k] = $value;
            }
        }
        return $original;
    }
}
