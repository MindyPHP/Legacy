<?php

namespace Mindy\Table\Columns;

use Mindy\Helper\Traits\RenderTrait;

/**
 * Class TemplateColumn
 * @package Mindy\Table
 */
class TemplateColumn extends Column
{
    use RenderTrait;
    /**
     * @var string template path
     */
    public $template;
    /**
     * @var array extra data for template rendering
     */
    public $extra = [];

    public function getValue($record)
    {
        return self::renderTemplate($this->template, array_merge([
            'value' => parent::getValue($record),
            'record' => $record,
            'table' => $this->table
        ], $this->extra));
    }
}