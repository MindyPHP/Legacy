<?php

namespace Mindy\Middleware\Middleware;

use Exception;
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
 * @date 13/04/14.04.2014 17:12
 */
interface IMiddleware
{
    public function processRequest(Request $request);

    /**
     * Event owner RenderTrait
     * @param \Mindy\Http\Request $request
     * @param $output string
     */
    public function processView(Request $request, $output);

    /**
     * @param Exception $exception
     * @void
     */
    public function processException(Exception $exception);

    /**
     * @param Request $request
     * @return mixed
     */
    public function processResponse(Request $request);
}
