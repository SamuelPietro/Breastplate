<?php

namespace Src\Core;

use Closure;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;

/**
 * Class responsible for managing the creation and resolution of dependencies.
 */
class Container implements ContainerInterface
{
    /**
     * Dependency mapping.
     *
     * @var array
     */
    private array $bindings = [];

    /**
     * Registers a dependency in the container.
     *
     * @param string          $abstract The abstract dependency name.
     * @param string|Closure|null $concrete The concrete dependency name or a Closure to resolve the dependency.
     */
    public function bind(string $abstract, Closure|string $concrete = null): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Retrieves a resolved instance of the dependency.
     *
     * @param string $id The dependency identifier.
     * @return mixed The resolved instance of the dependency.
     * @throws Exception If the dependency is not registered in the container.
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new Exception("The dependency [$id] is not registered in the container.");
        }

        $concrete = $this->bindings[$id];

        if ($concrete instanceof Closure) {
            // If the dependency is a Closure, we call the anonymous function to resolve it
            return $concrete($this);
        }

        // If the dependency is a string, assume it is a class name and instantiate it
        return $this->resolveClass($concrete);
    }

    /**
     * Checks if a dependency is registered in the container.
     *
     * @param string $id The dependency identifier.
     * @return bool True if the dependency is registered, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    /**
     * Instantiates the given class and resolves its dependencies.
     *
     * @param string $className The name of the class to instantiate.
     * @return mixed The instantiated class with resolved dependencies.
     * @throws Exception If the class cannot be instantiated or its dependencies cannot be resolved.
     */
    private function resolveClass(string $className): mixed
    {
        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            throw new Exception("The class [$className] cannot be instantiated.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            // If the class has no constructor, we can directly instantiate it
            return new $className();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyType = $parameter->getType();

            if ($dependencyType === null) {
                // If the dependency type is missing, throw an exception
                throw new Exception("The dependency [$parameter->name] of class [$className] cannot be resolved.");
            }

            $dependencyClassName = $dependencyType->getName();

            // Resolve the dependency recursively
            $dependencies[] = $this->get($dependencyClassName);
        }

        // Instantiate the class with resolved dependencies
        return $reflection->newInstanceArgs($dependencies);
    }
}
