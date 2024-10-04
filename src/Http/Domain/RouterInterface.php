<?php

namespace App\Http\Domain;

use App\Commons\Context;

interface RouterInterface
{
    /**
     * Get the singleton instance of the Router.
     * @return RouterInterface The singleton instance of the Router.
     */
    public static function getInstance(): RouterInterface;

    /**
     * Register a GET route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function get(string $route, array|callable $action);

    /**
     * Register a POST route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function post(string $route, array|callable $action);

    /**
     * Register a PUT route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function put(string $route, array|callable $action);

    /**
     * Register a DELETE route.
     * @param string $route The route to register.
     * @param array|callable $action The action to execute when the route is requested.
     * @return void
     */
    public function delete(string $route, array|callable $action);
    
    /**
     * Dispatch the request to the appropriate controller.
     * @param Context $context The context of the application.
     * @return void
     */
    public function dispatch(Context $context);
}