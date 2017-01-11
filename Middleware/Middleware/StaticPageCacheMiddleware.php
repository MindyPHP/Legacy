<?php

namespace Mindy\Middleware\Middleware;

use Mindy\Base\Mindy;
use Mindy\Helper\Alias;
use Mindy\Http\Request;

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
 * @date 11/04/14.04.2014 16:48
 */
class StaticPageCacheMiddleware extends Middleware
{
    /**
     * @var int default cache timeout 5 min
     */
    public $timeout = 300000;

    public function processRequest(Request $request)
    {
        $fileName = ($request->path == '/' ? 'index' : basename($request->path)) . '.html';
        $dir = str_replace('/', '.', ltrim(dirname($request->path), '/'));
        $cachePath = Alias::get('App.runtime.static.' . $request->host . '.' . $dir);
        $path = $cachePath . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($path)) {
            echo file_get_contents($path);
            Mindy::app()->end();
        }
    }

    public function processView(Request $request, $output)
    {
        $fileName = ($request->path == '/' ? 'index' : basename($request->path)) . '.html';
        $dir = str_replace('/', '.', ltrim(dirname($request->path), '/'));
        $cachePath = Alias::get('App.runtime.static.' . $request->host . '.' . $dir);
        if (!is_dir($cachePath)) {
            @mkdir($cachePath, 0777, true);
        }
        $path = $cachePath . DIRECTORY_SEPARATOR . $fileName;
        if (!file_exists($path) || (time() - fileatime($path)) > $this->timeout) {
            file_put_contents($cachePath . DIRECTORY_SEPARATOR . $fileName, $output);
        }
        return $output;
    }
}
