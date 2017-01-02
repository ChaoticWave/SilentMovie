<?php namespace ChaoticWave\SilentMovie\Providers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\SilentMovie\Services\ElasticService;

class ElasticServiceProvider extends BaseServiceProvider
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /** @inheritdoc */
    const ALIAS = 'sm.elastic';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function register()
    {
        $this->singleton(static::ALIAS,
            function($app, $config = []) {
                return new ElasticService($app, $config);
            });
    }
}
