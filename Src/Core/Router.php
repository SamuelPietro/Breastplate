<?php

namespace pFrame\Src\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use pFrame\Src\Exceptions\ErrorHandler;

/**
 * Class Router
 * Class responsible for routing requests to registered routes.
 */
class Router
{
    private array $routes = [];
    private ErrorHandler $errorHandler;
    private Container $container;

    /**
     * Router constructor.
     *
     * @param Container $container The dependency injection container.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Container $container)
    {
        $this->errorHandler = $container->get(ErrorHandler::class);;
        $this->container = $container;
    }

    /**
     * Add a new route to the router.
     *
     * @param string $method         The HTTP method (GET, POST, etc.).
     * @param string $path           The route path.
     * @param string $controllerName The controller name.
     * @param string $action         The controller action.
     */
    public function addRoute(string $method, string $path, string $controllerName, string $action): void
    {
        $this->routes[$method][$path] = [
            'controller' => $controllerName,
            'action' => $action,
            'path' => $path,
        ];
    }

    /**
     * Dispatches the request to the appropriate controller action.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path The request path.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function dispatch(string $method, string $path): void
    {
        $route = $this->findRoute($method, $path);

        if ($route) {
            $controllerName = $route['controller'];

            // Use the container to resolve the controller instance
            $controller = $this->container->get($controllerName);

            $action = $route['action'];

            $params = $this->extractRouteParams($route['path'], $path);

            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        $this->errorHandler->handleNotFound();
    }

    /**
     * Find a route that matches the given method and path.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path   The request path.
     *
     * @return array|null The matched route or null if no route is found.
     */
    private function findRoute(string $method, string $path): ?array
    {
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $route) {
                $pattern = $this->getPatternFromRoute($routePath);
                if (preg_match($pattern, $path)) {
                    return $route;
                }
            }
        }

        return null;
    }

    /**
     * Get the regular expression pattern from a route path.
     *
     * @param string $routePath The route path.
     *
     * @return string The regular expression pattern.
     */
    private function getPatternFromRoute(string $routePath): string
    {
        $pattern = preg_replace('/\//', '\\/', $routePath);
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return $pattern;
    }

    /**
     * Extract the route parameters from the given path.
     *
     * @param string $routePath The route path.
     * @param string $path      The request path.
     *
     * @return array The extracted route parameters.
     */
    private function extractRouteParams(string $routePath, string $path): array
    {
        $pattern = $this->getPatternFromRoute($routePath);
        preg_match($pattern, $path, $matches);

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}