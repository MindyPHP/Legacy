<?php
/**
 * Author: Falaleev Maxim
 * Email: max@studio107.ru
 * Company: http://en.studio107.ru
 * Date: 17/03/16
 * Time: 13:53
 */

namespace Mindy\Finder;

use Exception;
use Mindy\Finder\Finder\ITemplateFinder;
use Mindy\Finder\Finder\ThemeTemplateFinder;
use Mindy\Helper\Creator;
use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;

/**
 * TODO склеить TemplateFinder и ThemeTemplateFinder так как оба выполняют одинаковую функцию за исключением переменной $theme
 * Class Finder
 * @package Mindy\Finder
 */
class Finder
{
    use Configurator, Accessors;

    /**
     * Template finders
     * @var \Mindy\Finder\Finder\ITemplateFinder[]
     */
    private $_finders = [];
    /**
     * @var array of string
     */
    private $_paths = [];

    /**
     * @param array $finders
     * @throws Exception
     */
    public function setFinders(array $finders = [])
    {
        foreach ($finders as $config) {
            $finder = Creator::createObject($config);

            if (($finder instanceof ITemplateFinder) === false) {
                throw new Exception("Unknown template finder");
            }

            $this->_finders[] = $finder;
        }
    }

    /**
     * @param $templatePath
     * @return mixed
     */
    public function find($templatePath)
    {
        /** @var \Mindy\Finder\Finder\ITemplateFinder $finder */
        $templates = [];
        foreach ($this->_finders as $finder) {
            $template = $finder->find($templatePath);
            if ($template !== null) {
                $templates[] = $template;
            }
        }
        return array_shift($templates);
    }

    /**
     * @return array of string
     */
    public function getPaths()
    {
        if (empty($this->_paths)) {
            foreach ($this->_finders as $finder) {
                $this->_paths = array_merge($this->_paths, $finder->getPaths());
            }
        }
        return $this->_paths;
    }

    /**
     * @return null|string
     */
    public function getTheme()
    {
        foreach ($this->_finders as $finder) {
            if ($finder instanceof ThemeTemplateFinder) {
                return $finder->getTheme();
            }
        }

        return null;
    }
}
