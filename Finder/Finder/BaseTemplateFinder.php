<?php
/**
 * Author: Falaleev Maxim
 * Email: max@studio107.ru
 * Company: http://en.studio107.ru
 * Date: 17/03/16
 * Time: 13:53
 */

namespace Mindy\Finder\Finder;

use Mindy\Helper\Alias;
use Mindy\Helper\Traits\Configurator;

/**
 * Class BaseTemplateFinder
 * @package Mindy\Finder\Finder
 */
abstract class BaseTemplateFinder implements ITemplateFinder
{
    use Configurator;

    /**
     * @var string
     */
    public $basePath;

    public function init()
    {
        if (empty($this->basePath)) {
            $this->basePath = Alias::get('App');
        }
    }

    /**
     * @param $templatePath
     * @return null|string absolute path of template if founded
     */
    abstract public function find($templatePath);

    /**
     * @return array of available template paths
     */
    abstract public function getPaths();
}
