<?php namespace ChaoticWave\SilentMovie\Documents;

/**
 * An elasticsearch-specific document that represents a single search "hit"
 */
class HitDocument extends TypedDocument
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string The document type
     */
    protected $type;
    /**
     * @var double The score of the hit
     */
    protected $score;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param array $hit A single search response hit
     */
    public function __construct($hit = [])
    {
        $this->type = array_get($hit, '_type');
        $this->id = array_get($hit, '_id');
        $this->score = array_get($hit, '_score');

        parent::__construct(array_get($hit, '_index'), array_get($hit, '_source', array_get($hit, 'fields', [])));
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     *
     * Returns the designated document type for this class
     *
     * @return string|bool The document type
     */
    public function getDocumentType()
    {
        return $this->type;
    }

    /**
     * @return double
     */
    public function getScore()
    {
        return $this->score;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     *
     * @inheritdoc
     */
    public function dump()
    {
        return array_merge(parent::dump(),
            [
                'score' => $this->score,
            ]);
    }
}
