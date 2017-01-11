<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 04/08/16
 * Time: 17:34
 */

namespace Mindy\Form\Widget;

use Mindy\Form\Widget;

class SelectWidget extends Widget
{
    /**
     * @return string
     */
    public function render()
    {
        $js = "<script type='text/javascript'>$('#{$this->getHtmlId()}').selectize();</script>";
        $field = $this->getField();
        return $field->renderInput() . $js;
    }
}