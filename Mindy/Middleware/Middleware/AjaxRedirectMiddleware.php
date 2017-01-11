<?php

namespace Mindy\Middleware\Middleware;

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
class AjaxRedirectMiddleware extends Middleware
{
    public function processResponse(Request $request)
    {
        if ($request->getIsPost() && $request->getIsAjax()) {
            header("Location: " . $request->getPath());
            header("HTTP/1.1 278 OK", true, 278);
        }
    }
}
