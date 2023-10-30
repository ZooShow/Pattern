<?php

abstract class Middleware
{
    private ?Middleware $next = null;

    public function linkWith(Middleware $next): Middleware
    {
        $this->next = $next;

        return $next;
    }

    public function check(string $request): bool
    {
        if ($this->next === null) {
            return false;
        }

        return $this->next->check($request);
    }
}

class AdminMiddleware extends Middleware
{
    public function check(string $request): bool
    {
        if (str_contains($request, 'admin')) {
            echo 'ADMIN' . PHP_EOL;

            return true;
        }

        echo 'not ADMIN' . PHP_EOL;
        return parent::check($request);
    }
}

class ApiMiddleware extends Middleware
{
    public function check(string $request): bool
    {
        if (str_contains($request, 'api')) {
            echo 'API' . PHP_EOL;
            return true;
        }

        echo 'not API' . PHP_EOL;
        return parent::check($request);
    }
}

class WebMiddleware extends Middleware
{
    public function check(string $request): bool
    {
        if (str_contains($request, 'web')) {
            echo 'WEB' . PHP_EOL;

            return true;
        }
        echo 'not WEB' . PHP_EOL;

        return parent::check($request);
    }
}

class Server {
    private Middleware $middleware;

    public function setMiddleware(Middleware $middleware)
    {
        $this->middleware = $middleware;
    }

    public function validRequest(string $request)
    {
        return $this->middleware->check($request);
    }
}

$apiMiddleware = new ApiMiddleware();
$webMiddleware = new WebMiddleware();
$adminMiddleware = new AdminMiddleware();

$webMiddleware->linkWith($apiMiddleware)->linkWith($adminMiddleware);

$server = new Server();
$server->setMiddleware($webMiddleware);

echo $server->validRequest('asd');
echo PHP_EOL;

echo $server->validRequest('api');