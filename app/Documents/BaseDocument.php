<?php namespace ChaoticWave\SilentMovie\Documents;

use Carbon\Carbon;
use ChaoticWave\BlueVelvet\Utility\Encoding;
use ChaoticWave\SilentMovie\Contracts\DocumentLike;
use Illuminate\Support\Collection;

abstract class BaseDocument extends Collection
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string The index of this document
     */
    protected $index;
    /**
     * @var string
     */
    protected $id;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param array|mixed|DocumentLike $items
     */
    public function __construct($items = [])
    {
        $this->index = array_pull($items, 'index') ?: config('media.elastic.index');

        if (empty($this->index)) {
            throw new \InvalidArgumentException('No index name has been configured.');
        }

        //  Boot the document
        $this->boot($items);

        //  Load the rest into the collection
        parent::__construct($items);
    }

    /**
     * A chance to massage the items before the go in the collection
     *
     * @param array $items
     *
     * @return array
     */
    protected function boot(&$items = [])
    {
        if (!empty($items)) {
            foreach ($items as $_key => $_value) {
                if ($_value !== '0000-00-00' && $_value !== '00/00/00') {
                    if ('date' === strtolower(substr($_key, -4))) {
                        $items[$_key] = Carbon::parse($_value)->toIso8601String();
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Returns the contents encoded
     *
     * @param string     $to      Target character encoding
     * @param array|null $objects Array of keys into $this[] that are not scalar
     *
     * @return array|string
     */
    public function toEncodedArray($to = 'UTF-8', $objects = null)
    {
        foreach ($_data = Encoding::encode($this->toArray(), $to) as $_key => $_value) {
            if (!empty($_value) && is_string($_value) && in_array($_key, $objects)) {
                try {
                    //  Try PHP serialize
                    if (false !== ($_raw = unserialize($_value))) {
                        $_data[$_key] = $_raw;
                        continue;
                    }
                } catch (\Exception $_ex) {
                    //  Ignored
                }

                try {
                    //  Try JSON
                    if (false !== ($_raw = json_decode($_value, true)) && JSON_ERROR_NONE === json_last_error()) {
                        $_data[$_key] = $_raw;
                        continue;
                    }
                } catch (\Exception $_ex) {
                    //  Ignored
                }
            }
        }

        return $_data;
    }

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
    public function toParamsArray($body = true, $refresh = true, $upsert = false, $merge = null)
    {
        //  Make sure we are in UTF8
        $_data = array_map(function($value) {
            return is_scalar($value) ? utf8_encode($value) : $value;
        },
            $this->toArray());

        $_body = $upsert ? [
            'doc'           => $_data,
            'doc_as_upsert' => true,
        ] : $_data;

        $_params = [
            'index' => $this->getIndex(),
            'body'  => $_body,
        ];

        if ($refresh) {
            $_params['refresh'] = true;
        }

        if (!empty($_id = $this->getId())) {
            $_params['id'] = $_id;
        }

        if (!empty($merge) && is_array($merge)) {
            $_params = array_merge($merge, $_params);
        }

        if (!$body) {
            array_forget($_params, 'body');
        }

        return $_params;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return BaseDocument
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return BaseDocument
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Checks if a word is worth saving for search
     *
     * @param string $word
     *
     * @return bool
     */
    protected function searchableWord($word)
    {
        //  Strip non-alpha characters
        $word = preg_replace('/[^a-zA-Z0-9]/', null, strtolower(trim($word)));

        return !empty($word) && !in_array($word, ['yes', 'no', 'and', 'or']);
    }

    /**
     * Dump the contents of the document to an array
     *
     * @return array
     */
    public function dump()
    {
        return [
            'id'      => $this->id,
            'index'   => $this->index,
            '_source' => $this->all(),
        ];
    }
}
