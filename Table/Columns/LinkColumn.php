<?php

namespace Mindy\Table\Columns;

use Closure;
use Mindy\Exception\Exception;

/**
 * Class LinkColumn
 * @package Mindy\Table
 */
class LinkColumn extends Column
{
    /**
     * @var string
     */
    public $template = "<a href='{url}' title='{value}'>{text}</a>";
    /**
     * @var Closure
     */
    public $route;

    public $text;

    /**
     * @param $record
     * @return string
     * @throws \Exception
     */
    public function getValue($record)
    {
        $value = parent::getValue($record);
        $text = $this->text;
        if (!empty($text) && $text instanceof Closure) {
            $text = $text($value);
        } else {
            $text = $value;
        }
        if (empty($this->route)) {
            throw new Exception('Missing route');
        }
        $url = $this->route->__invoke($record);
        return $url ? strtr($this->template, [
            '{value}' => $value,
            '{text}' => $text,
            '{url}' => $url
        ]) : $value;
    }
}
