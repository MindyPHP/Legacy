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

/**
 * Class AppTemplateFinder
 * @package Mindy\Finder
 */
class AppTemplateFinder extends TemplateFinder
{
    /**
     * @var array
     */
    public $modulesDirs = [];

    public function init()
    {
        parent::init();
        if (empty($this->modulesDirs)) {
            $this->modulesDirs = [
                Alias::get('App.Modules')
            ];
        }
    }

    /**
     * @param $templatePath
     * @return null|string absolute path of template if founded
     */
    public function find($templatePath)
    {
        $tmp = explode(DIRECTORY_SEPARATOR, $templatePath);
        if (count($tmp) > 1) {
            $app = ucfirst(array_shift($tmp));

            foreach ($this->modulesDirs as $dir) {
                $path = join(DIRECTORY_SEPARATOR, [$dir, $app, $this->templatesDir, $templatePath]);
                if (is_file($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * @return array of available template paths
     */
    public function getPaths()
    {
        $paths = [];
        foreach ($this->modulesDirs as $dir) {
            $extra = glob($dir . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . $this->templatesDir);
            if (!$extra) {
                continue;
            }
            $paths = array_merge($paths, $extra);
        }
        return $paths;
    }
}
