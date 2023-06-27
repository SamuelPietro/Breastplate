<?php

namespace Src\Core;

use Src\Exceptions\ErrorHandler;
use Psr\Container\ContainerInterface;

/**
 * Class Router
 * Class responsible for routing requests to registered routes.
 */
class Router
{
    private array $routes = [];
    private ErrorHandler $errorHandler;
    private ContainerInterface $container;

    public function __construct(ErrorHandler $errorHandler, ContainerInterface $container)
    {
        $this->errorHandler = $errorHandler;
        $this->container = $container;
    }

    public function addRoute(string $method, string $path, string $controllerName, string $action): void
    {
        $this->routes[$method][$path] = [
            'controller' => $controllerName,
            'action' => $action,
            'path' => $path,
        ];
    }

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

    private function getPatternFromRoute(string $routePath): string
    {
        $pattern = preg_replace('/\//', '\\/', $routePath);
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return $pattern;
    }

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
