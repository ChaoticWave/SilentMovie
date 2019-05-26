<?php namespace ChaoticWave\SilentMovie\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\SilentMovie\Providers\ImdbServiceProvider;
use ChaoticWave\SilentMovie\Responses\PeopleResponse;
use ChaoticWave\SilentMovie\Responses\TitleResponse;
use ChaoticWave\SilentMovie\Services\ImdbService;

/**
 * @see ImdbService
 *
 * @method static PeopleResponse searchPeople($term, $options = [])
 * @method static TitleResponse searchTitle($term, $options = [])
 * @method static TitleResponse search($term, $options = [])
 */
class ImdbApi extends BaseFacade
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    protected static function getFacadeAccessor()
    {
        return ImdbServiceProvider::ALIAS;
    }
}
