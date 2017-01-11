<?php

namespace Mindy\Table;

use Mindy\Helper\Creator;
use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;
use Mindy\Orm\QuerySet;
use Mindy\Pagination\Pagination;
use Mindy\Table\Columns\RawColumn;

/**
 * Class Table
 * @package Mindy\Table
 */
abstract class Table
{
    use Configurator, Accessors;

    /**
     * @var bool
     */
    public $enablePagination = true;
    /**
     * @var \Mindy\Orm\Model|\Mindy\Orm\QuerySet|array
     */
    public $data = [];
    /**
     * @var array
     */
    public $html = [];
    /**
     * @var string
     */
    public $template = '<table {html}>{caption}{header}{footer}{body}</table>{pager}';
    /**
     * @var array pagination config
     */
    public $paginationConfig = [];
    /**
     * @var \Mindy\Table\Columns\Column[]
     */
    protected $_columns = null;
    /**
     * @var string Table caption
     */
    public $caption = '';
    /**
     * @var bool
     */
    public $enableHeader = true;
    /**
     * @var bool
     */
    public $enableFooter = false;
    /**
     * @var \Mindy\Pagination\Pagination
     */
    private $_pager;

    public function getColumns()
    {
        return [];
    }

    public function __construct($data, array $config = [])
    {
        $this->data = $data;
        $this->configure($config);
        $this->init();
    }

    public function getInitColumns()
    {
        if (is_null($this->_columns)) {
            $this->_columns = [];

            foreach ($this->getColumns() as $name => $config) {
                if (is_numeric($name)) {
                    if (is_string($config)) {
                        $name = $config;
                        $config = ['class' => RawColumn::className()];
                    }
                } else if (!is_array($config)) {
                    $config = ['class' => $config];
                }

                $this->_columns[$name] = Creator::createObject(array_merge([
                    'name' => $name,
                    'table' => $this,
                ], $config));
            }
        }

        return $this->_columns;
    }


    public function render()
    {
        return strtr($this->template, [
            '{html}' => $this->getHtmlAttributes(),
            '{caption}' => $this->renderCaption(),
            '{header}' => $this->renderHeader(),
            '{footer}' => $this->renderFooter(),
            '{body}' => $this->renderBody(),
            '{pager}' => $this->getPager()
        ]);
    }

    public function renderHeader()
    {
        $header = '';
        if ($this->enableHeader) {
            foreach ($this->getInitColumns() as $column) {
                $header .= $column->renderHeadCell();
            }
            return strtr('<thead><tr>{header}</tr></thead>', [
                '{header}' => $header
            ]);
        }
        return $header;
    }

    public function renderFooter()
    {
        $footer = '';
        if ($this->enableFooter) {
            foreach ($this->getInitColumns() as $column) {
                $footer .= $column->renderFootCell();
            }
            return strtr('<tfoot><tr>{footer}</tr></tfoot>', [
                '{footer}' => $footer
            ]);
        }
        return $footer;
    }

    public function renderBody()
    {
        $body = '';
        $data = $this->getData();
        foreach ($data as $item) {
            $row = '';
            foreach ($this->getInitColumns() as $column) {
                $row .= $column->renderCell($item);
            }
            $body .= strtr('<tr {html}>{row}</tr>', [
                '{html}' => $this->formatHtmlAttributes($this->getRowHtmlAttributes($item)),
                '{row}' => $row
            ]);
        }
        return strtr('<tbody>{body}</tbody>', [
            '{body}' => $body
        ]);
    }

    public function renderCaption()
    {
        if ($this->caption) {
            return strtr('<caption>{caption}</caption>', [
                '{caption}' => $this->caption
            ]);
        }
        return '';
    }

    /**
     * @param array $html
     * @return string
     */
    public function formatHtmlAttributes(array $html)
    {
        if (is_string($html)) {
            return $html;
        } else if (is_array($html)) {
            $out = '';
            foreach ($html as $name => $value) {
                $out .= is_numeric($name) ? " $value" : " $name='$value'";
            }
            return $out;
        }

        return '';
    }

    public function getHtmlAttributes()
    {
        return $this->formatHtmlAttributes($this->html);
    }

    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * @param $record
     * @return array
     */
    public function getRowHtmlAttributes($record)
    {
        return [];
    }

    public function getPager()
    {
        if ($this->enablePagination === false) {
            return null;
        }

        if ($this->_pager === null) {
            $this->_pager = new Pagination($this->data, $this->paginationConfig);
        }

        return $this->_pager;
    }

    public function getData()
    {
        if ($this->enablePagination) {
            return $this->getPager()->paginate();
        } else {
            return is_a($this->data, QuerySet::className()) ? $this->data->all() : $this->data;
        }
    }

    public function count()
    {
        return is_a($this->data, QuerySet::className()) ? $this->data->count() : count($this->data);
    }
}