<?php

declare(strict_types=1);

namespace App\Http\Infraestructure;

use App\Shared\Context;
use App\Http\Constants\HttpCodes;
use App\Http\Constants\HttpMethods;
use App\Http\Domain\RouterInterface;

class Router implements RouterInterface
{
    /** 
     * @var array $routes Stores the routes of the application.
     */
    private static array $routes = [];

    /** 
     * @var Router|null $instance Singleton instance of the Router.
     */
    private static $instance = null;

    /** 
     * Private constructor to prevent direct instatiation.
     */
    private function __construct() {}

    /**
     * Get the singleton instance of the Router.
     * @return RouterInterface The singleton instance of the Router.
     */
    public static function getInstance(): RouterInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    /**
     * Register a route.
     * @param string $route The route to register.
     * @param string $method The HTTP method of the route.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    private function register(string $route, string $method, array|callable $action): void
    {
        $route = trim($route, '/');

        self::$routes[$method][$route] = $action;
    }

    /**
     * Register a GET route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function get(string $route, array|callable $action)
    {
        $this->register($route, HttpMethods::GET, $action);
    }

    /**
     * Register a POST route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function post(string $route, array|callable $action)
    {
        $this->register($route, HttpMethods::POST, $action);
    }

    /**
     * Register a PUT route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function put(string $route, array|callable $action)
    {
        $this->register($route, HttpMethods::PUT, $action);
    }

    /**
     * Register a DELETE route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function delete(string $route, array|callable $action)
    {
        $this->register($route, HttpMethods::DELETE, $action);
    }

    /**
     * Dispatch the request to the appropriate controller.
     * @param Context $context The application context.
     */
    public function dispatch(Context $context)
    {

        // Get the requested route.
        $requestedRoute = trim($_SERVER['REQUEST_URI'], '/') ?? '/';

        // Remove query string from the requested route.
        $requestedRoute = explode('?', $requestedRoute)[0];

        // Get the routes for the requested method.
        $routes = self::$routes[$_SERVER['REQUEST_METHOD']];

        foreach ($routes as $route => $action) {
            // Transform the route to a regex pattern.
            $routeRegex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches) {
                return isset($matches[1]) ? '(' . $matches[2] . ')' : '([a-zA-Z0-9_-]+)';
            }, $route);

            // Add delimiters to the regex pattern.
            $routeRegex = '@^' . $routeRegex . '$@';

            // Check if the requested route matches the route pattern.
            if (preg_match($routeRegex, $requestedRoute, $matches)) {
                // Get all requested path params values.
                array_shift($matches);
                $routeParamsValues = $matches;

                // Find all route params names from route and save in $routeParamsNames
                $routeParamsNames = [];
                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $route, $matches)) {
                    $routeParamsNames = $matches[1];
                }

                // Combine route params names and values in $routeParams
                $routeParams = array_combine($routeParamsNames, $routeParamsValues);
                $queryParamans = $_REQUEST;

                if (isset($queryParamans['page'])) {
                    $context->session['pagination']['page'] = $queryParamans['page'];
                    unset($queryParamans['page']);
                }

                if (isset($queryParamans['limit'])) {
                    $context->session['pagination']['limit'] = $queryParamans['limit'];
                    unset($queryParamans['limit']);
                }

                if (isset($queryParamans['order'])) {
                    $context->session['order'] = explode(',', $queryParamans['order']);
                    unset($queryParamans['order']);
                }

                // Merge route params and query params
                $routeParams = array_merge($routeParams, $queryParamans);

                // Get Json post data
                $post = file_get_contents("php://input");
                $post = json_decode($post, true) ?? [];


                $routeParams = array_merge($routeParams, $post);

                // Create a DataRequest object with the request method, requested route and route params.
                $dataRequest = new DataRequest($_SERVER['REQUEST_METHOD'], $requestedRoute, $routeParams);

                return  $this->executeAction($action, [$dataRequest, $context]);
            }
        }

        // Return a 404 response if the route is not found.
        return new DataResponse('Route not found', HttpCodes::NOT_FOUND);
    }

    /**
     * Execute the action of the route.
     * @param array|callable $action The action to execute.
     * @param array $routeParams The route params.
     * @return mixed The result of the action.
     */
    private function executeAction(array|callable $action, array $routeParams)
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $routeParams);
        } else if (is_array($action)) {
            return call_user_func_array([new $action[0], $action[1]], $routeParams);
        }
    }
}
