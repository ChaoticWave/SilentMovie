<?php namespace ChaoticWave\SilentMovie\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\SilentMovie\Providers\ElasticServiceProvider;

/**
 * @see \ChaoticWave\SilentMovie\Services\ElasticSearchService
 */
class Elastic extends BaseFacade
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    protected static function getFacadeAccessor()
    {
        return ElasticServiceProvider::ALIAS;
    }
}
