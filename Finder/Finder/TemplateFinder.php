<?php
/**
 * Author: Falaleev Maxim
 * Email: max@studio107.ru
 * Company: http://en.studio107.ru
 * Date: 17/03/16
 * Time: 13:53
 */

namespace Mindy\Finder\Finder;

/**
 * Class TemplateFinder
 * @package Mindy\Finder
 */
class TemplateFinder extends BaseTemplateFinder
{
    /**
     * @var string
     */
    public $templatesDir = 'templates';

    /**
     * @param $templatePath
     * @return null|string absolute path of template if founded
     */
    public function find($templatePath)
    {
        $path = join(DIRECTORY_SEPARATOR, [$this->basePath, $this->templatesDir, $templatePath]);
        if(is_file($path)) {
            return $path;
        }

        return null;
    }

    /**
     * @return array of available template paths
     */
    public function getPaths()
    {
        return [
            join(DIRECTORY_SEPARATOR, [$this->basePath, $this->templatesDir])
        ];
    }
}
