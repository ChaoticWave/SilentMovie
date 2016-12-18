<?php namespace ChaoticWave\SilentMovie\Contracts;

/**
 * A contract shared by all documents
 */
interface DocumentLike
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Builds a $params array for use with the Elasticsearch client. Includes index, id, and body elements
     *
     * @param bool       $body    If true, a "body" element with the contents included
     * @param bool       $refresh If true, a "refresh" => true is added
     * @param bool       $upsert  If true, "body" will contain {"doc":"old body", "doc_as_upsert":true}
     * @param array|null $merge   Additional data to merge with params array. This method's data takes precedence
     *
     * @return array
     */
    public function toParamsArray($body = true, $refresh = true, $upsert = false, $merge = null);

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return array
     */
    public function toJson();

    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id The document ID
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getIndex();

    /**
     * @return string
     */
    public function getDocumentType();

    /**
     * Returns any defined mappings
     *
     * @return array
     */
    public function getMapping();

    /**
     * Returns the document in ES array format
     *
     * @return array
     */
    public function dump();

    /**
     * Get an item from the document by key.
     *
     * @param mixed      $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Put an item in the document by key.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this
     */
    public function put($key, $value);

    /**
     * Returns all items in the document
     *
     * @return array
     */
    public function all();

    /**
     * Determine if an item exists in the document
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return bool
     */
    public function contains($key, $value = null);
}
