<?php

namespace System\Kernel;

use System\Kernel\Router\Router;
use Throwable;

class Application
{
    public function __construct()
    {
        defined('DEBUG_MODE') or define('DEBUG_MODE', false);

        if (DEBUG_MODE) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            ini_set('log_errors', '1');
            error_reporting(E_ALL);
        } else {
            @ini_set('display_errors', '0');
            @ini_set('display_startup_errors', '0');
            @ini_set('log_errors', '1');
            @error_reporting(0);
        }
    }

    /**
     * Run the application
     * @throws Throwable
     */
    public function init(): void
    {
        try {
            $routeAction = (new Router())->getRouteAction($_SERVER['REQUEST_URI']);
            $controller = new ($routeAction->getClassName());
            echo call_user_func_array([$controller, $routeAction->getActionName()], [$routeAction->getParameter()]);
        }
        catch (Throwable $e) {
            file_put_contents(__DIR__ . '/../../logs/error.log', $e, FILE_APPEND);
            file_put_contents(__DIR__ . '/../../logs/error.log', "\n\n", FILE_APPEND);
            throw $e;
        }
    }
}