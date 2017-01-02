<?php namespace ChaoticWave\SilentMovie\Documents;

use ChaoticWave\BlueVelvet\Utility\Uri;
use ChaoticWave\SilentMovie\Contracts\DocumentLike;

abstract class EntityDocument extends TypedDocument
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string
     */
    protected $sourceId;
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

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function __construct($items = [], $overrideType = null)
    {
        //  Kajigger the ID
        $this->sourceId = array_pull($items, 'id') ?: array_pull($items, 'sourceId');
        $this->title = array_pull($items, 'title');
        $this->titleDescription = array_pull($items, 'title_description') ?: array_pull($items, 'titleDescription');
        $this->episodeTitle = array_pull($items, 'episode_title') ?: array_pull($items, 'episodeTitle');
        $this->description = array_pull($items, 'description');
        $this->extra = array_pull($items, 'extra');
        $this->source = array_pull($items, 'source');

        //  Load the rest into the collection
        parent::__construct($items);
    }
}
