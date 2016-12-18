<?php namespace ChaoticWave\SilentMovie\Documents;

use ChaoticWave\BlueVelvet\Utility\Uri;
use ChaoticWave\SilentMovie\Contracts\DocumentLike;

abstract class TypedDocument extends BaseDocument implements DocumentLike
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /** @inheritdoc */
    const DOCUMENT_TYPE = false;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array Index type/field mappings
     */
    protected $mapping;
    /**
     * @var string An override type for this document
     */
    protected $overrideType;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function __construct($items = [], $overrideType = null)
    {
        if ($overrideType && $overrideType !== $this->getDocumentType()) {
            $this->overrideType = $overrideType;
        }

        //  Load the rest into the collection
        parent::__construct($items);
    }

    /** @inheritdoc */
    public function getDocumentType()
    {
        if (empty($this->overrideType) && empty(static::DOCUMENT_TYPE)) {
            throw new \RuntimeException('The document type is not set for this object.');
        }

        return $this->overrideType ?: static::DOCUMENT_TYPE;
    }

    /**
     * Returns the URI of this document in "/index/type/id" format
     *
     * @return null|string
     */
    public function getDocumentUri()
    {
        return Uri::segment([$this->index, $this->getDocumentType(), $this->id]);
    }

    /** @inheritdoc */
    public function toParamsArray($body = true, $refresh = true, $upsert = false, $merge = null)
    {
        $_params = parent::toParamsArray($body, $refresh, $upsert);

        //  Add in the type cuz we know it's there
        $_params['type'] = $this->getDocumentType();

        return $_params;
    }

    /** @inheritdoc */
    public function getMapping()
    {
        //  Dynamically map document
        foreach ($this->all() as $_key => $_value) {
            //  Map non-scalar fields
            if (null !== $_value && !is_scalar($_value)) {
                $this->mapping['properties'][$_key] = ['type' => 'object', 'dynamic' => true];
            }
        }

        return $this->mapping;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isSameDocumentType($type)
    {
        $_thisType = static::make()->getDocumentType();

        return $type == $_thisType;
    }

    /** @inheritdoc */
    public function dump()
    {
        return array_merge(parent::dump(), ['type' => $this->getDocumentType(),]);
    }
}
