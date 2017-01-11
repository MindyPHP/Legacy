<?php

namespace Mindy\Logger\Formatters;

use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;
use Monolog\Formatter\LineFormatter as MonologLineFormatter;

/**
 * Class LineFormatter
 * @package Mindy\Logger
 */
class LineFormatter
{
    use Accessors, Configurator;

    /**
     * @var \Monolog\Formatter\LineFormatter
     */
    public $formatter;

    public $allowInlineLineBreaks = false;

    public $format = null;

    public $dateFormat = null;

    public function init()
    {
        $this->formatter = new MonologLineFormatter($this->format, $this->dateFormat, $this->allowInlineLineBreaks);
    }
}
