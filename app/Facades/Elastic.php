<?php namespace ChaoticWave\SilentMovie\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\SilentMovie\Providers\ElasticServiceProvider;

/**
 * @see \ChaoticWave\SilentMovie\Services\ElasticSearchService
 * @method static int count($index, $type, $params = [])
 * @method static bool delete($index, $type = null, $id = null)
 * @method static bool deleteIndex($index, $params = [])
 * @method static bool exists($index, $id, $type = '_all')
 * @method static bool indexExists($index)
 * @method static array find(&$document)
 * @method static array get($index, $id, $type = '_all', $params = [])
 * @method static array getSettings($index, $params = [])
 * @method static array getSource($index, $id, $type = '_all')
 * @method static array index($document, $params = [])
 * @method static bool indexRemap($document, $params = [])
 * @method static array info($params = [])
 * @method static array putMapping($index, $type, $params = [])
 * @method static array putSettings($index, $settings = [], $params = [])
 * @method static array query($index, $type = null, $query = null, $params = [])
 * @method static array search($index, $type = null, $query = null, $params = [])
 * @method static array upsert($document, $params = [])
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
