<?php

namespace Mindy\Table\Columns;

/**
 * Class NumberColumn
 * @package Mindy\Table
 */
class NumberColumn extends Column
{
    /**
     * @var int
     */
    public $decimals = 0;
    /**
     * @var string delimiter
     */
    public $decPoint = '.';
    /**
     * @var string
     */
    public $thousandsSep = ',';

    public function getValue($record)
    {
        $value = parent::getValue($record);
        return ($this->emptyValue && !$value) ? '' : number_format($value, $this->decimals, $this->decPoint, $this->thousandsSep);
    }
} 