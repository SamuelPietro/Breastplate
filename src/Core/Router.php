<?php

namespace Src\Core;
use Exception;

class Router {
    private array $routes = array();

    public function addRoute($method, $path, $controller, $action): void
    {
        $this->routes[] = array(
            'method' => $method,
            'path' => $path,
            'controller' => $this->toStudlyCaps($controller),
            'action' => $this->toCamelCase($action)
        );
    }

    /**
     * @throws Exception
     */
    public function route($method, $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] == $method && $route['path'] == $path) {
                $controllerClass = "App\Controllers\\" . $route['controller'];
                $controller = new $controllerClass();
                $action = $route['action'];
                $controller->$action();
                return;
            }
        }
        throw new Exception("Route not found: $method $path");
    }

    private function toCamelCase($string): string
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    private function toStudlyCaps($string): array|string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
}
