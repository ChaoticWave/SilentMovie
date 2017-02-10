<?php namespace ChaoticWave\SilentMovie\Responses;

use ChaoticWave\SilentMovie\Enums\MediaDataSources;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * A core class for various media API responses
 */
class Entity implements Arrayable, Jsonable
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $episodeTitle;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $titleDescription;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var array
     */
    protected $extra;
    /**
     * @var int The source of the data
     * @see MediaDataSources
     */
    protected $source;
    /**
     * @var string
     */
    protected $ingested;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $data = Collection::make($data)->toArray();

        $this->source = array_pull($data, 'source', MediaDataSources::IMDB);
        $this->id = array_pull($data, 'id');
        $this->name = array_pull($data, 'name');
        $this->episodeTitle = array_pull($data, 'episode_title');
        $this->title = array_pull($data, 'title');
        $this->titleDescription = array_pull($data, 'title_description');
        $this->description = array_pull($data, 'description');
        $this->ingested = array_pull($data, 'ingested_at');

        $this->extra = $data;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getEpisodeTitle()
    {
        return $this->episodeTitle;
    }

    /**
     * @return string
     */
    public function getTitleDescription()
    {
        return $this->titleDescription;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getIngested()
    {
        return $this->ingested;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'title'             => $this->title,
            'title_description' => $this->titleDescription,
            'description'       => $this->description,
            'episode_title'     => $this->episodeTitle,
            'extra'             => $this->extra,
            'source'            => $this->source,
            'ingested_at'       => $this->ingested,
        ];
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
