<?php

namespace Src\Core;

use Exception;

/**
 * Class Router
 * Responsible for handling the routes and dispatching them to the proper controllers and actions.
 */
class Router {
    /**
     * @var array $routes An array of all the defined routes
     */
    private array $routes = array();

    /**
     * Adds a new route to the Router
     *
     * @param string $method The HTTP method for this route, for example "GET" or "POST"
     * @param string $path The URL path for this route
     * @param string $controller The name of the Controller class that this route should trigger
     * @param string $action The name of the action (method) in the Controller class that this route should trigger
     */
    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        // Add the route to the routes array
        $this->routes[] = array(
            'method' => $method,
            'path' => $path,
            // Transform the controller name to StudlyCaps format
            'controller' => $this->toStudlyCaps($controller),
            // Transform the action name to camelCase format
            'action' => $this->toCamelCase($action)
        );
    }

    /**
     * Tries to route the incoming request to the proper Controller and action
     *
     * @param string $method The HTTP method of the incoming request
     * @param string $path The URL path of the incoming request
     *
     * @throws Exception If the incoming request cannot be routed to a Controller and action
     */
    public function route(string $method, string $path): void
    {
        // Loop through all the routes
        foreach ($this->routes as $route) {
            // If the current route matches the incoming request
            if ($route['method'] == $method && $route['path'] == $path) {
                // Build the fully-qualified class name for the Controller
                $controllerClass = "App\Controllers\\" . $route['controller'];
                // Instantiate the Controller
                $controller = new $controllerClass();
                // Get the name of the action to trigger
                $action = $route['action'];
                // Call the action on the Controller
                $controller->$action();
                return;
            }
        }
        // If the incoming request cannot be routed, throw an Exception
        throw new Exception("Route not found: $method $path");
    }

    /**
     * Transforms a string to camelCase format
     *
     * @param string $string The input string
     *
     * @return string The input string transformed to camelCase format
     */
    private function toCamelCase(string $string): string
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * Transforms a string to StudlyCaps format
     *
     * @param string $string The input string
     *
     * @return string The input string transformed to StudlyCaps format
     */
    private function toStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
}
