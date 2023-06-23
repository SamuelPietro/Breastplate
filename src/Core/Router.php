<?php

namespace Src\Core;

use Src\Exceptions\ErrorHandler;
use Src\Extensions\FormatTextExtension;

class Router
{
    private array $routes = [];
    private ErrorHandler $errorHandler;

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

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

    public function route(string $method, string $path): void
    {
        $route = $this->findMatchingRoute($method, $path);

        if ($route === null) {
            $this->errorHandler->handleNotFound();
            return;
        }

        $this->dispatchRoute($route, $path);
    }

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

    private function dispatchRoute(array $route, string $path): void
    {
        $controllerClassName = "App\Controllers\\" . $route['controller'];
        $controller = new $controllerClassName();
        $action = $route['action'];

        $params = $this->extractRouteParams($route['path'], $path);

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            $this->errorHandler->handleNotFound();
        }
    }

    private function extractRouteParams(string $routePath, string $path): array
    {
        $params = [];
        $pattern = '/{(\w+)}/';
        preg_match_all($pattern, $routePath, $matches);

        if (!empty($matches[1])) {
            $paramNames = $matches[1];
            $pathParts = explode('/', trim($path, '/'));

            foreach ($paramNames as $index => $paramName) {
                if (isset($pathParts[$index])) {
                    $params[] = $pathParts[$index];
                }
            }
        }

        return $params;
    }
}
