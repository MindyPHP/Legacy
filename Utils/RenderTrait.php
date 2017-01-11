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
 * @date 09/04/14.04.2014 16:26
 */

namespace Mindy\Utils;

use Exception;
use Mindy\Base\Mindy;

trait RenderTrait
{
    public function renderString($source, array $data = [])
    {
        return Mindy::app()->getComponent('template')->renderString($source, $this->mergeData($data));
    }

    protected static function mergeData($data)
    {
        if(is_array($data) === false) {
            $data = [];
        }
        $app = Mindy::app();
        return array_merge($data, [
            'request' => $app->getComponent('request'),
            'user' => $app->getUser()
        ]);
    }

    public static function renderTemplate($view, array $data = [])
    {
        return Mindy::app()->getComponent('template')->render($view, self::mergeData($data));
    }

    /**
     * @deprecated use renderTemplate
     * @param $view
     * @param array $data
     */
    public static function renderStatic($view, array $data = [])
    {
        return self::renderTemplate($view, $data);
    }

    /**
     * Renders a view file.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $_viewFile_ view file
     * @param array $_data_ data to be extracted and made available to the view file
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function renderInternal($_viewFile_, $_data_ = null)
    {
        // we use special variable names here to avoid conflict when extracting data
        if (is_array($_data_)) {
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $_data_;
        }

        ob_start();
        ob_implicit_flush(false);
        require($_viewFile_);
        return ob_get_clean();
    }
}
