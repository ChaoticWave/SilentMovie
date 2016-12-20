<?php namespace ChaoticWave\SilentMovie\Responses;

use ChaoticWave\SilentMovie\Contracts\ApiResponseLike;
use ChaoticWave\SilentMovie\Documents\Entity;
use ChaoticWave\SilentMovie\Enums\MediaDataSources;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class BaseApiResponse implements ApiResponseLike
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array The raw response
     */
    protected $raw;
    /**
     * @var array The response
     */
    protected $response;
    /**
     * @var int The source  of the response
     * @see MediaDataSources
     */
    protected $source;
    /**
     * @var array The top level groups
     */
    protected $mapping = [];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        $this->raw = $this->response = $this->getArrayableItems($response);
        $this->source = array_pull($this->response, 'source');

        $_mapping = $this->getMapping();

        if (empty($_mapping) && null !== ($_mapping = array_pull($this->response, 'mapping'))) {
            $this->mapping = $_mapping;
        }
    }

    /**
     * @return array
     */
    public function mappedArray()
    {
        $_array = [];

        foreach ($this->getMapping() as $_prop) {
            $_array[$_prop] = $this->{$_prop};
        }

        return $_array;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        $_array = [];

        foreach (['source', 'response', 'raw'] as $_prop) {
            $_array[$_prop] = $this->{$_prop};
        }

        foreach ($this->mappedArray() as $_name => $_value) {
            if (is_array($_value)) {
                /**
                 * @var int    $_index
                 * @var Entity $_entity
                 */
                foreach ($_value as $_index => $_entity) {
                    $_array[$_name][] = $_entity instanceof Entity ? $_entity->toArray() : $_entity;
                }
            } else {
                $_array[$_name] = $_value;
            }
        }

        return $_array;
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param  mixed $items
     *
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if (is_array($items)) {
            return $items;
        }

        if ($items instanceof Collection) {
            return $items->all();
        }

        if ($items instanceof Arrayable) {
            return $items->toArray();
        }

        if ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        }

        if ($items instanceof \Traversable) {
            return iterator_to_array($items);
        }

        return (array)$items;
    }

    /**
     * @return array
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }
}
