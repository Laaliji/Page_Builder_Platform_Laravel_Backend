
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\HandleCors::class,
    ],

    'api' => [
        \App\Http\Middleware\HandleCors::class,
    ],
];

protected $middlewareAliases = [
    'cors' => \App\Http\Middleware\Cors::class,
];

protected $middleware = [
    \App\Http\Middleware\HandleCors::class,
];