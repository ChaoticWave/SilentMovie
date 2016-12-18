<?php namespace ChaoticWave\SilentMovie\Providers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\SilentMovie\Services\ImdbService;

class ImdbServiceProvider extends BaseServiceProvider
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /** @inheritdoc */
    const ALIAS = 'sm.imdb';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function register()
    {
        $this->singleton(static::ALIAS,
            function($app, $config = []) {
                return new ImdbService($app, $config);
            });
    }
}
