<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Foundation\Application;
use Closure;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        '/admin*', '/login', '/logout'
    ];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            if ((auth()->check() && auth()->user()->user_type == 'admin') || (auth()->check() && auth()->user()->user_type == 'staff') || $this->inExceptArray($request)) {
                return $next($request);
            } else {
                return abort(503);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
