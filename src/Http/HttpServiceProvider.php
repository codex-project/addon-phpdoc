<?php

namespace Codex\Addon\Phpdoc\Http;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Codex\Addon\Phpdoc\Http\Controllers\Api\V1';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group([
            'prefix'    => config('codex.base_route') . '/api/v1/' . config('codex-phpdoc.route_prefix'),
            'namespace' => $this->namespace,
            'as'        => 'codex.phpdoc.api.v1.',
        ], function ($router) {
            require realpath(__DIR__ . '/routes.php');
        });
    }
}
