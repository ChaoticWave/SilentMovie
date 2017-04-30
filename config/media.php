<?php
return [
    /** The default service to use */
    'default' => 'imdb',
    /** The supported API services */
    'apis'    => [
        'imdb' => [
            'service'   => 'imdb',
            'api-key'   => env('IMDB_API_KEY'),
            'endpoints' => [
                'search' => 'http://imdb.wemakesites.net/api/search?q={query}&api_key={api_key}',
                'get'    => 'http://imdb.wemakesites.net/api/{resource_id}?api_key={api_key}',
                'person' => 'http://www.omdbapi.com/?r=json&t={query}&_={time}',
                //'person' => 'http://www.imdb.com/xml/find?json=1&nm=on',
                'title'  => 'http://www.omdbapi.com/?r=json&plot=short&t={query}&_={time}',
                //'title'  => 'http://www.imdb.com/xml/find?json=1&tt=on',
            ],
        ],
    ],
    /** Elasticsearch configuration */
    'elastic' => [
        'hosts'         => ['http://elastic:changeme@localhost:9200',],
        'index'         => 'sm_media',
        'strict-search' => false,
    ],
];
