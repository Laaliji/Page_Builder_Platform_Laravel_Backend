<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Specify which of the database connections below you wish to use as your
    | default connection for database operations. This is the connection that
    | will be utilized unless another is specified when you execute a query.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections set up for your application.
    | Examples for configuring each database platform supported by Laravel are
    | provided to make development simple. You can adjust these as needed.
    |
    | All database work in Laravel is done through the PHP PDO facilities,
    | so ensure you have the driver for your particular database of choice
    | installed on your machine before you begin development.
    |
    */

    'connections' => [

        'mysql' => [
            'driver'          => 'mysql',
            'url'             => env('DATABASE_URL'),
            'host'            => env('DB_HOST', '127.0.0.1'),
            'port'            => env('DB_PORT', '3306'),
            'database'        => env('DB_DATABASE', 'pagebuilder'),
            'username'        => env('DB_USERNAME', 'root'),
            'password'        => env('DB_PASSWORD', ''),
            'unix_socket'     => env('DB_SOCKET', ''),
            'charset'         => env('DB_CHARSET', 'utf8mb4'),
            'collation'       => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix'          => '',
            'prefix_indexes'  => true,
            'strict'          => true,
            'engine'          => null,
            'options'         => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA    => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => false,
            ]) : [],
        ],

        // Other database connections...
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | Keeps track of all the migrations that have already run for your
    | application. Using this information, we can determine which of the
    | migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open-source, fast, and advanced key-value store that provides
    | a richer body of commands than typical key-value systems like Memcached.
    | Laravel makes it easy to dig right in with Redis.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix'  => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];