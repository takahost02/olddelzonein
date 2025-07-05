<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'delzone.in'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'delzone_cms'),
            'username' => env('DB_USERNAME', 'delzone_cms'),
            'password' => env('DB_PASSWORD', 'U{2L2YL{Uo9+'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            //'dump' => [ 'dump_binary_path' => 'C:/xampps/mysql/bin/'],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'delzone.in'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', 'delzone.in'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
