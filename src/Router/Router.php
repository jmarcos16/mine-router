<?php

namespace Jmarcos16\Mine\Router;

use Jmarcos16\Mine\Exceptions\RouteNotFoundException;
use Reflection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private array $routes = [];
    private Container $container;

    public function __construct(
        array $controllers
    ) {

        $this->container = new Container();

        if (!empty($controllers)) {
            foreach ($controllers as $controller) {
                $this->addRoutesFromController($controller);
            }
        }
    }

    public function handle(Request $request)
    {
        $uri    = $request->getPathInfo();
        $method = $request->getMethod();

        foreach ($this->routes as $key => $route) {
            if(in_array($method, $route['methods']) && $this->matchRoute($key, $uri, $params)) {
                $this->makeInstance($route['controller'], $route['actions'], $params);
                return;
            }
        }

        throw new RouteNotFoundException('Route not found');
    }

    private function makeInstance($controller, string $actions, array $params)
    {
        if($controller)
        {
            $instance =
        }
    }

    private function matchRoute(string $path, string $uri, &$params): bool
    {
        $path  = preg_replace('/{(\w+)}/', '(\w+)', $path);
        $regex = "#^$path$#";

        if (!preg_match($regex, $uri, $matches)) {
            return false;
        }

        $params = array_slice($matches, 1);

        return true;
    }

    private function addRoutesFromController($controller)
    {
        $reflection = new \ReflectionClass($controller);

        $actions = $reflection->getMethods();

        foreach ($actions as $actions) {
            $attributes = $actions->getAttributes();

            foreach ($attributes as $attribute) {
                if ($attribute->getName() === 'Jmarcos16\Mine\Attribute\Route') {
                    $route = $attribute->newInstance();
                    $this->addRoute($route->getUri(), $controller, $actions->getName(), $route->getMethods());
                }
            }
        }
    }

    private function addRoute(string $uri, $controller, string $actions, array $methods = ['GET']): void
    {
        $this->routes[$uri] = [
            'controller' => $controller,
            'actions'    => $actions,
            'methods'    => $methods,
        ];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}