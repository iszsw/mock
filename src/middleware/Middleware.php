<?php

namespace iszsw\mock\middleware;

use iszsw\mock\middleware\relation\WithMock;
use think\App;

class Middleware
{
    use WithMock;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function handle($request, \Closure $next)
    {
        $this->request = $request;

        if (true === $data = $this->generate() )
        {
            return $next($request);
        }

        $this->app->config->set(['type' => 'console'], 'trace');
        return json($data);

    }


}

