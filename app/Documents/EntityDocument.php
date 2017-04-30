<?php namespace ChaoticWave\SilentMovie\Documents;

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
    /**
     * @var array An array of associated entity ids
     */
    protected $associations;

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
        $this->associations = array_pull($items, 'associations', []);

        //  Load the rest into the collection
        parent::__construct($items);
    }

    /**
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceId
     *
     * @return EntityDocument
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return EntityDocument
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEpisodeTitle()
    {
        return $this->episodeTitle;
    }

    /**
     * @param string $episodeTitle
     *
     * @return EntityDocument
     */
    public function setEpisodeTitle($episodeTitle)
    {
        $this->episodeTitle = $episodeTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return EntityDocument
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitleDescription()
    {
        return $this->titleDescription;
    }

    /**
     * @param string $titleDescription
     *
     * @return EntityDocument
     */
    public function setTitleDescription($titleDescription)
    {
        $this->titleDescription = $titleDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return EntityDocument
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     *
     * @return EntityDocument
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param int $source
     *
     * @return EntityDocument
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    /**
     * @param array $associations
     *
     * @return EntityDocument
     */
    public function setAssociations($associations)
    {
        $this->associations = $associations;

        return $this;
    }
}
