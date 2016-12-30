<?php namespace ChaoticWave\SilentMovie\Services;

use Carbon\Carbon;
use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Contracts\SearchesMediaApis;
use ChaoticWave\SilentMovie\Database\Models\MediaQuery;
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
        /** @var MediaQuery $_query */
        if (null !== ($_query = MediaQuery::where('user_id', 0)->where('source_nbr', MediaDataSources::IMDB)->where('query_text', $text)->first())) {
            return new PeopleResponse($_query->response_text);
        }

        $_result = $_json = $this->httpGet(array_get($this->config, 'endpoints.person'), array_merge($options, ['q' => urlencode($text)]));
        is_string($_json) && $_result = json_decode($_json, true);
        $_result['source'] = MediaDataSources::IMDB;

        try {
            MediaQuery::create([
                'user_id'            => 0,
                'source_nbr'         => MediaDataSources::IMDB,
                'query_text'         => $text,
                'response_type_text' => 'title',
                'response_text'      => $_result,
                'response_date'      => Carbon::now(),
            ]);
        } catch (\Exception $_ex) {
            $this->logError('Exception creating media query row: ' . $_ex->getMessage());
        }

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
