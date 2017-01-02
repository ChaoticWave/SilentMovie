<?php
return [
    /** The default service to use */
    'default' => 'imdb',
    /** The supported API services */
    'apis'    => [
        'imdb' => [
            'service'   => 'imdb',
            'endpoints' => [
                'person' => 'http://www.imdb.com/xml/find?json=1&nm=on',
                'title'  => 'http://www.imdb.com/xml/find?json=1&tt=on',
            ],
        ],
        'omdb' => [
            'service'   => 'omdb',
            'endpoints' => [
                'person' => '',
                'title'  => '',
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
