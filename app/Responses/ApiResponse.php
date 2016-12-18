<?php namespace ChaoticWave\SilentMovie\Responses;

use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Documents\HitDocument;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class ApiResponse implements Arrayable, Jsonable, ApiResponseLike
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var int Response time in milliseconds
     */
    protected $took;
    /**
     * @var bool If the request timed out or not
     */
    protected $timedOut;
    /**
     * @var array Shards touched
     */
    protected $shards;
    /**
     * @var HitDocument[] Hits of search
     */
    protected $hits;
    /**
     * @var int The number of hits
     */
    protected $hitCount;
    /**
     * @var double
     */
    protected $maxScore;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param array $response The response from the server
     */
    public function __construct($response = [])
    {
        $this->took = array_get($response, 'took');
        $this->timedOut = array_get($response, 'timed_out', false);
        $this->shards = array_get($response, '_shards', []);
        $this->hitCount = array_get($response, 'hits.total', 0);
        $this->maxScore = array_get($response, 'hits.max_score');

        $this->loadHits($response);
    }

    /**
     * Parse a search response and return any hits in an array
     *
     * @param array $response The response from the server
     *
     * @return HitDocument[]|boolean An array of hits or FALSE if none
     */
    public static function parseHits($response = [])
    {
        $_response = new static($response);

        return $_response->hasHits() ? $_response->getHits() : false;
    }

    /**
     * Determine if the search was successful
     *
     * @return bool
     */
    public function hasHits()
    {
        return $this->getHitCount() > 0;
    }

    /**
     * @return int
     */
    public function getTook()
    {
        return $this->took;
    }

    /**
     * @return boolean
     */
    public function isTimedOut()
    {
        return $this->timedOut;
    }

    /**
     * @return array
     */
    public function getShards()
    {
        return $this->shards;
    }

    /**
     * @return HitDocument[]
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * @return int
     */
    public function getHitCount()
    {
        return $this->hitCount;
    }

    /**
     * @return float
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }

    /**
     * @param array $response
     */
    protected function loadHits($response)
    {
        $this->hits = [];

        foreach (array_get($response, 'hits.hits', []) as $_hit) {
            $this->hits[] = new HitDocument($_hit);
        }
    }

    /**
     * Convert this object to an array
     *
     * @return array
     */
    public function toArray()
    {
        $_array = [
            'took'      => $this->took,
            'timed_out' => $this->timedOut,
            'shards'    => $this->shards,
            'hit_count' => $this->hitCount,
            'max_score' => $this->maxScore,
        ];

        foreach ($this->hits as $_hit) {
            $_array['hits'][] = $_hit->toArray();
        }

        return $_array;
    }

    /**
     * Convert this object to JSON
     *
     * @param int $options
     * @param int $depth
     *
     * @return string
     */
    public function toJson($options = 0, $depth = 512)
    {
        return json_encode($this->toArray(), $options, $depth);
    }
}
