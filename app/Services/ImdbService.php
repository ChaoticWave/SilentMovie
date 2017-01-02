<?php namespace ChaoticWave\SilentMovie\Services;

use Carbon\Carbon;
use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Contracts\SearchesMediaApis;
use ChaoticWave\SilentMovie\Database\Models\MediaQuery;
use ChaoticWave\SilentMovie\Documents\DocumentFactory;
use ChaoticWave\SilentMovie\Enums\MediaDataSources;
use ChaoticWave\SilentMovie\Responses\PeopleResponse;
use ChaoticWave\SilentMovie\Responses\ResponseFactory;
use ChaoticWave\SilentMovie\Responses\TitleResponse;

class ImdbService extends BaseService implements SearchesMediaApis
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use Curly;

    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var string
     */
    const PERSON_ENDPOINT_NAME = 'person';
    /**
     * @var string
     */
    const TITLE_ENDPOINT_NAME = 'title';

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
    public function searchPeople($text, $options = [])
    {
        return $this->doSearch($text, array_get($this->config, 'endpoints.' . static::PERSON_ENDPOINT_NAME), $options);
    }

    /**
     * Find titles that contain $text
     *
     * @param string $text    The text to search for
     * @param array  $options Options for the call
     *
     * @return ApiResponseLike
     */
    public function searchTitle($text, $options = [])
    {
        return $this->doSearch($text, array_get($this->config, 'endpoints.') . static::TITLE_ENDPOINT_NAME, $options);
    }

    /**
     * @param string $endpoint
     * @param string $query
     * @param array  $options
     *
     * @return bool|PeopleResponse|TitleResponse
     */
    protected function doSearch($query, $endpoint, $options = [])
    {
        if (false !== ($_cache = $this->checkQueryCache($query))) {
            return $_cache;
        }

        $_result = $_json = $this->httpGet(array_get($this->config, 'endpoints.' . $endpoint, array_merge($options, ['q' => urlencode($query)])));
        is_string($_json) && $_result = json_decode($_json, true);

        $_result['source'] = MediaDataSources::IMDB;
        $_result['type'] = $endpoint;

        $this->storeQuery($query, $_result);

        return ResponseFactory::make($_result);
    }

    protected function addPerson($person)
    {
    }

    /**
     * @param string $query
     * @param        null  int|$source
     *
     * @return bool|\ChaoticWave\SilentMovie\Responses\PeopleResponse
     */
    protected function checkQueryCache($query, $source = MediaDataSources::IMDB)
    {
        $_cache = MediaQuery::whereRaw('user_id = :user_id AND source_nbr = :source_nbr AND query_text = :query_text',
            [':user_id' => 0, ':source_nbr' => $source, 'query_text' => $query])->first();

        return $_cache ? new PeopleResponse($_cache->response_text) : false;
    }

    /**
     * @param string      $text
     * @param array|null  $result
     * @param string|null $type
     * @param int|null    $source
     *
     * @return MediaQuery
     */
    protected function storeQuery($text, $result = null, $type = null, $source = MediaDataSources::IMDB)
    {
        if (empty($result['source'])) {
            $result['source'] = $source ?: MediaDataSources::IMDB;
        }

        if (empty($result['type'])) {
            $result['type'] = $type ?: 'title';
        }

        try {
            /** @var MediaQuery $_model */
            $_model = MediaQuery::create([
                'user_id'            => 0,
                'source_nbr'         => $result['source'],
                'query_text'         => $text,
                'response_type_text' => $result['type'],
                'response_text'      => $result,
                'response_date'      => Carbon::now(),
            ]);

            if ($_model && null !== ($_doc = DocumentFactory::make($result))) {
                //Elastic::index($_doc);
            }

            return $_model;
        } catch (\Exception $_ex) {
            $this->logError('Exception creating media query row: ' . $_ex->getMessage());
        }

        return null;
    }
}
