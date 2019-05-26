<?php
return [
    /** The default service to use */
    'default' => 'imdb',
    /** The supported API services */
    'apis'    => [
        'imdb' => [
            'service'   => 'imdb',
            'api-key'   => env('OMDB_API_KEY'),
            'endpoints' => [
                'get'    => 'http://www.omdbapi.com/?r=json&s={query}&_={time}&apikey={api_key}',
                'search' => 'http://www.omdbapi.com/?r=json&s={query}&_={time}&apikey={api_key}',
                'person' => 'http://www.omdbapi.com/?r=json&s={query}&_={time}&apikey={api_key}',
                'title'  => 'http://www.omdbapi.com/?r=json&t={query}&_={time}&apikey={api_key}',
            ],
        ],
    ],
    /** Elasticsearch configuration */
    'elastic' => [
        'hosts'         => [env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'),],
        'index'         => 'sm_media',
        'strict-search' => false,
    ],
];
