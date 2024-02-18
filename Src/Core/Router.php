<?php

namespace pFrame\Src\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use pFrame\Src\Exceptions\ErrorHandler;

/**
 * Class Router
 * This class is responsible for routing requests to registered routes.
 */
class Router
{
    /**
     * @var array The routes of the application.
     */
    private array $routes = [];

    /**
     * @var ErrorHandler The instance of the error handler.
     */
    private ErrorHandler $errorHandler;

    /**
     * @var Container The instance of the dependency injection container.
     */
    private Container $container;

    /**
     * @var array The stack of middleware.
     */
    private array $middlewares = [];

    /**
     * Router constructor.
     *
     * @param Container $container The dependency injection container.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Container $container)
    {
        $this->errorHandler = $container->get(ErrorHandler::class);
        $this->container = $container;
    }

    /**
     * Add a new route to the router.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $path The route path.
     * @param string $controllerName The name of the controller.
     * @param string $action The action of the controller.
     */
    public function addRoute(string $method, string $path, string $controllerName, string $action, array $middlewares = []): void
    {
        $this->routes[$method][$path] = [
            'controller' => $controllerName,
            'action' => $action,
            'path' => $path,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Add a new middleware to the router.
     *
     * @param callable $middleware The middleware function.
     */
    public function addMiddleware(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Run the registered middlewares.
     *
     * @throws Exception If a middleware throws an exception.
     */
    private function runMiddlewares(): void
    {
        foreach ($this->middlewares as $middleware) {
            call_user_func($middleware);
        }
    }

    /**
     * Dispatches the request to the appropriate controller action.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path The request path.
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function dispatch(string $method, string $path): void
    {
        $this->runMiddlewares();
        $route = $this->findRoute($method, $path);

        if (!$route) {
            $this->errorHandler->handleNotFound();
            return;
        }
        foreach ($route['middlewares'] as $middleware) {
            if (is_callable([$middleware, 'handle'])) {
                call_user_func([$middleware, 'handle']);
            }
        }

        $controller = $this->container->get($route['controller']);
        $action = $route['action'];
        $params = $this->extractRouteParams($route['path'], $path);

        if (!method_exists($controller, $action)) {
            $this->errorHandler->handleNotFound();
            return;
        }

        call_user_func_array([$controller, $action], $params);
    }

    /**
     * Find a route that matches the given method and path.
     *
     * @param string $method The HTTP method of the request.
     * @param string $path The request path.
     *
     * @return array|null The matched route or null if no route is found.
     */
    private function findRoute(string $method, string $path): ?array
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $routePath => $route) {
            if (preg_match($this->getPatternFromRoute($routePath), $path)) {
                return $route;
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
        return '/^' . $pattern . '$/';
    }

    /**
     * Extract the route parameters from the given path.
     *
     * @param string $routePath The route path.
     * @param string $path The request path.
     *
     * @return array The extracted route parameters.
     */
    private function extractRouteParams(string $routePath, string $path): array
    {
        preg_match($this->getPatternFromRoute($routePath), $path, $matches);
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}