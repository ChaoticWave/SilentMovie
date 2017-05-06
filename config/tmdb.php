<?php
/**
 * @package       php-tmdb\laravel
 * @author        Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
return [
    /**
     * Api key
     */
    'api_key'    => env('TMDB_API_KEY'),
    /** Read-only API Key */
    'ro_api_key' => env('TMDB_RO_API_KEY'),
    /**
     * Client options
     */
    'options'    => [
        /**
         * Use https
         */
        'secure' => true,
        /**
         * Cache
         */
        'cache'  => [
            'enabled' => true,
            // Keep the path empty or remove it entirely to default to storage/tmdb
            'path'    => base_path('bootstrap/cache/tmdb'),
        ],
        /**
         * Log
         */
        'log'    => [
            'enabled' => true,
            // Keep the path empty or remove it entirely to default to storage/logs/tmdb.log
            'path'    => storage_path('logs/tmdb.log'),
            'handler' => new \Monolog\Handler\ChromePHPHandler(),
        ],
    ],
];
