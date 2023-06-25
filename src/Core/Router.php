<?php

namespace Src\Core;

use Src\Exceptions\ErrorHandler;
use Src\Extensions\FormatTextExtension;

/**
 *
 * Class Router
 *
 * Class responsible for routing requests to registered routes.
 */
class Router
{
    /**
     * @var array
     */
    private array $routes = [];

    /**
     * @var ErrorHandler
     */
    private ErrorHandler $errorHandler;

    /**
     * Router constructor.
     *
     * @param ErrorHandler $errorHandler The error handler object.
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * Adds a route to the router.
     *
     * @param string $method     The HTTP method for the route.
     * @param string $path       The path pattern for the route.
     * @param string $controller The controller class name.
     * @param string $action     The action method name.
     * @return void
     */
    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $formatText = new FormatTextExtension();
        $path = preg_quote($path, '/');
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $formatText->toPascalCase($controller),
            'action' => $formatText->toLowerCase($action),
        ];
    }

    /**
     * Routes the request to the appropriate controller and action.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path   The path of the request.
     * @return void
     */
    public function route(string $method, string $path): void
    {
        $route = $this->findMatchingRoute($method, $path);

        if ($route === null) {
            $this->errorHandler->handleNotFound();
            return;
        }

        $this->dispatchRoute($route, $path);
    }

    /**
     * Finds the matching route for the given method and path.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path   The path of the request.
     * @return array|null The matching route or null if no match is found.
     */
    private function findMatchingRoute(string $method, string $path): ?array
    {
        foreach ($this->routes as $route) {
            $routePath = preg_replace('/{(\w+)}/', '(\w+)', $route['path']);
            if (preg_match("#^$routePath$#", $path, $matches) && $route['method'] === $method) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Dispatches the route to the appropriate controller action.
     *
     * @param array  $route The matched route.
     * @param string $path  The path of the request.
     * @return void
     */
    private function dispatchRoute(array $route, string $path): void
    {
        $controllerClassName = "App\Controllers\\" . $route['controller'];
        $controller = new $controllerClassName();
        $action = $route['action'];

        $params = $this->extractRouteParams($route['path'], $path);

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        }
        $this->errorHandler->handleNotFound();
    }

    /**
     * Extracts the route parameters from the path.
     *
     * @param string $routePath The path pattern of the route.
     * @param string $path      The path of the request.
     * @return array The extracted route parameters.
     */
    private function extractRouteParams(string $routePath, string $path): array
    {
        $params = [];
        $pattern = '/{(\w+)}/';
        preg_match_all($pattern, $routePath, $matches);

        $paramNames = $matches[1] ?? [];

        $pathParts = explode('/', trim($path, '/'));

        foreach ($paramNames as $index => $paramName) {
            if (isset($pathParts[$index])) {
                $params[] = $pathParts[$index];
            }
        }

        return $params;
    }
}
