<?php

namespace Src\Core;

use Exception;

/**
 * The Router class is responsible for handling the routes and dispatching them to the proper controllers and actions.
 */
class Router
{
    /**
     * An array of all the defined routes.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Adds a new route to the Router.
     *
     * @param string $method      The HTTP method for this route, for example "GET" or "POST".
     * @param string $path        The URL path for this route.
     * @param string $controller  The name of the Controller class that this route should trigger.
     * @param string $action      The name of the action (method) in the Controller class that this route should trigger.
     *
     * @return void
     */
    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        // Add the route to the routes array
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $this->toStudlyCaps($controller),
            'action' => $this->toCamelCase($action),
        ];
    }

    /**
     * Tries to route the incoming request to the proper Controller and action.
     *
     * @param string $method  The HTTP method of the incoming request.
     * @param string $path    The URL path of the incoming request.
     *
     * @throws Exception If the incoming request cannot be routed to a Controller and action.
     *
     * @return void
     */
    public function route(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            $routePath = preg_replace('/{(\w+)}/', '(\w+)', $route['path']);
            if (preg_match("#^$routePath$#", $path, $matches) && $route['method'] == $method) {
                $controllerClass = "App\Controllers\\" . $route['controller'];
                $controller = new $controllerClass();
                $action = $route['action'];
                array_shift($matches);
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        throw new Exception("Route not found: $method $path");
    }

    /**
     * Transforms a string to camelCase format.
     *
     * @param string $string  The input string.
     *
     * @return string The input string transformed to camelCase format.
     */
    private function toCamelCase(string $string): string
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * Transforms a string to StudlyCaps format.
     *
     * @param string $string  The input string.
     *
     * @return string The input string transformed to StudlyCaps format.
     */
    private function toStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
}
