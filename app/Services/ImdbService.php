<?php namespace ChaoticWave\SilentMovie\Services;

use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Contracts\SearchesMediaApis;
use ChaoticWave\SilentMovie\Enums\MediaDataSources;
use ChaoticWave\SilentMovie\Responses\PeopleResponse;
use ChaoticWave\SilentMovie\Responses\TitleResponse;

class ImdbService extends BaseService implements SearchesMediaApis
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use Curly;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array
     */
    protected $config;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array                                        $config
     */
    public function __construct($app, $config = [])
    {
        parent::__construct($app);

        $this->config = !empty($config) ? $config : config('media.apis.imdb');
    }

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Find people that contain $text
     *
     * @param string $text    The text to search for
     * @param array  $options Options for the call
     *
     * @return ApiResponseLike
     */
    public function searchPeople($text, $options = array())
    {
        $_result = $this->httpGet(array_get($this->config, 'endpoints.person'), array_merge($options, ['q' => urlencode($text)]));
        is_string($_result) && $_result = json_decode($_result, true);
        $_result['source'] = MediaDataSources::IMDB;

        return new PeopleResponse($_result);
    }

    /**
     * Find titles that contain $text
     *
     * @param string $text    The text to search for
     * @param array  $options Options for the call
     *
     * @return ApiResponseLike
     */
    public function searchTitle($text, $options = array())
    {
        $_result = $this->httpGet(array_get($this->config, 'endpoints.title'), array_merge($options, ['q' => urlencode($text)]));
        is_string($_result) && $_result = json_decode($_result, true);
        $_result['source'] = MediaDataSources::IMDB;

        return new TitleResponse($_result);
    }

    protected function addPerson($person)
    {
    }

}
