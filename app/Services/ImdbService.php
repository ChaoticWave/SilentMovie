<?php namespace ChaoticWave\SilentMovie\Services;

use Carbon\Carbon;
use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Contracts\SearchesMediaApis;
use ChaoticWave\SilentMovie\Database\Models\MediaEntity;
use ChaoticWave\SilentMovie\Database\Models\MediaQuery;
use ChaoticWave\SilentMovie\Documents\ResponseDocument;
use ChaoticWave\SilentMovie\Enums\MediaDataSources;
use ChaoticWave\SilentMovie\Facades\Elastic;
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
        return $this->doSearch($text, static::PERSON_ENDPOINT_NAME, $options);
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
        return $this->doSearch($text, static::TITLE_ENDPOINT_NAME, $options);
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
            return ResponseFactory::make($_cache);
        }

        $_result = $_json = $this->httpGet(array_get($this->config, 'endpoints.' . $endpoint),
            array_merge($options, ['q' => urlencode($query), '_' => time()]),
            [
                CURLOPT_HTTPHEADER => [
                    'content-type' => 'application/json',
                    'user-agent'   => \Request::server('http-user-agent'),
                ],
            ]);

        is_string($_json) && $_result = json_decode($_json, true);

        $_result = array_merge($_result,
            [
                'type'    => $endpoint,
                'details' => [
                    'response_time' => time(),
                    'media_source'  => MediaDataSources::IMDB,
                    'query_text'    => $query,
                    'endpoint'      => $endpoint,
                ],
            ]);

        $this->storeQuery($query, $_result, $endpoint, MediaDataSources::IMDB);

        return ResponseFactory::make($_result);
    }

    /**
     * @param string $query
     * @param        null  int|$source
     *
     * @return array|boolean
     */
    protected function checkQueryCache($query, $source = MediaDataSources::IMDB)
    {
        $_cache = MediaQuery::whereRaw('user_id = :user_id AND source_nbr = :source_nbr AND query_text = :query_text',
            [':user_id' => 0, ':source_nbr' => $source, 'query_text' => $query])->first();

        return empty($_cache) ? false : $_cache->response_text;
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
        $result['media_source'] = $source = array_get($result, 'media_source', $source ?: MediaDataSources::IMDB);
        $result['type'] = $type = array_get($result, 'type', $type ?: static::PERSON_ENDPOINT_NAME);

        $_model = null;

        try {
            /** @var MediaQuery $_model */
            $_model = MediaQuery::query()->create([
                'user_id'            => 0,
                'source_nbr'         => $source,
                'query_text'         => $text,
                'response_type_text' => $type,
                'response_text'      => $result,
                'response_date'      => Carbon::now(),
            ]);
        } catch (\Exception $_ex) {
            $this->logError('Exception creating media query row: ' . $_ex->getMessage());
        }

        $this->indexResponse($result, $_model);

        return $_model;
    }

    /**
     * @param array            $response
     * @param MediaEntity|null $model
     *
     * @return bool
     */
    protected function indexResponse($response, $model = null)
    {
        $document = new ResponseDocument($response);

        if (false === ($_result = Elastic::index($document))) {
            return false;
        }

        $this->logDebug('Response indexed', ['result' => $_result]);

        if (null !== $model && null !== ($_id = array_get($_result, '_id'))) {
            $model->update(['index_id_text' => $_id]);
        }

        return true;
    }
}
