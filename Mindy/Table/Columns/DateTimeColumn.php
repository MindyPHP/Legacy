<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 25/02/15 18:53
 */

namespace Mindy\Table\Columns;

class DateTimeColumn extends Column
{
    /**
     * @var string date time format
     */
    public $dateFormat = 'Y-m-d H:m:s';

    /**
     * @param $record
     * @return bool|string
     * @throws \Exception
     */
    public function getValue($record)
    {
        $value = parent::getValue($record);
        if ($value === null) {
            return '';
        } else if (is_numeric($value)) {
            return date($this->dateFormat, $value);
        } else {
            return date($this->dateFormat, strtotime($value));
        }
    }
}
