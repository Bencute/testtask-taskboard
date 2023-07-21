<?php

namespace System\Kernel\Router;

use Exception;
use System\Kernel\Config;

class Router
{
    private array $config;

    public function __construct()
    {
        $this->config = Config::get()['route'];
    }

    /**
     * @throws Exception
     */
    private function findRoute(array $routeParsed): RouteAction
    {
        foreach ($this->config as $route) {
            if (is_array($route['path'])) {
                if (count($routeParsed) == 2
                    && $route['path'][0] === $routeParsed[0])
                {
                    return new RouteAction($route['action'][0], $route['action'][1], $routeParsed[1]);
                }
            } else {
                if ($route['path'] === $routeParsed[0]) {
                    return new RouteAction($route['action'][0], $route['action'][1]);
                }
            }
        }

        throw new Exception('Route not found');
    }

    /**
     * @throws Exception
     */
    public function getRouteAction(string $uri): RouteAction
    {
        $routePath = explode('?', $uri)[0];

        $routePath = trim($routePath, '/\\');
        $routeParsed = [];
        if (!empty($routePath)) {
            $routeParsed = explode('/', $routePath);
        }

        if (!count($routeParsed)) {
            $routeParsed[] = '/';
        }

        return $this->findRoute($routeParsed);
    }
}