<?php namespace ChaoticWave\SilentMovie\Services;

use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\BlueVelvet\Traits\FiresEvents;
use ChaoticWave\SilentMovie\Contracts\DocumentLike;
use ChaoticWave\SilentMovie\Documents\DocumentFactory;
use ChaoticWave\SilentMovie\Documents\TypedDocument;
use ChaoticWave\SilentMovie\Responses\ElasticResponse;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Contracts\Foundation\Application;

class ElasticService extends BaseService
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use FiresEvents;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var Client One client to rule them all!
     */
    protected static $client;
    /**
     * @var bool If true, "type" will always be specified on searches, limited result
     */
    protected $strictSearch = false;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->strictSearch = config('elastic.strict-search', false);
    }

    /**
     * Indexes (stores) a document
     *
     * @param \ChaoticWave\SilentMovie\Contracts\DocumentLike $document
     * @param array                                           $params
     *
     * @return array|bool
     */
    public function index($document, $params = [])
    {
        if (null !== ($_id = $document->getId())) {
            if ($this->exists($document->getIndex(), $_id, $document->getDocumentType())) {
                $this->logDebug('[elastic.index] redirecting to upsert');

                return $this->upsert($document);
            }
        }

        $_params = array_merge($document->toParamsArray(), $params);

        try {
            $_response = static::getClient()->index($_params);

            $this->fireEvent('index', ['document' => $document->toArray(), 'response' => $_response]);

            return $_response;
        } catch (BadRequest400Exception $_ex) {
            $this->logError('[elastic.index] failure', ['message' => $_ex->getMessage(), 'code' => $_ex->getCode()]);
        }

        return false;
    }

    /**
     * @param DocumentLike $document
     * @param array        $params
     *
     * @return array|bool
     */
    public function upsert($document, $params = [])
    {
        //  Redirect to create if there is no ID
        if (!$document->getId()) {
            return $this->index($document, $params);
        }

        $_params = array_merge($document->toParamsArray(true, true, true), $params);

        try {
            $_response = static::getClient()->update($_params);
            $this->fireEvent('upsert', ['document' => $document->toArray(), 'response' => $_response]);

            return $_response;
        } catch (\Exception $_ex) {
            $this->logError('[elastic.upsert] failure: ' . $_ex->getMessage(), ['params' => array_except($_params, ['body'])]);
        }

        return false;
    }

    /**
     * Find a document where the source matches and return it's ID
     *
     * @param TypedDocument $document
     *
     * @return bool|mixed
     */
    public function find(&$document)
    {
        try {
            if (false !== ($_response = static::getClient()->get($document->toParamsArray(false, false)))) {
                if (false === ($_hits = ElasticResponse::parseHits($_response))) {
                    return false;
                }

                $document = DocumentFactory::make(array_first($_hits)->toArray());

                return $document->getId();
            }
        } catch (\Exception $_ex) {
        }

        //  Not found
        return false;
    }

    /**
     * Retrieve a document
     *
     * @param string      $index
     * @param string      $id
     * @param string|null $type
     * @param array       $params
     *
     * @return array|bool
     * @internal param \Determine\Module\Elastic\Contracts\DocumentLike $document
     */
    public function get($index, $id, $type = '_all', $params = [])
    {
        return static::getClient()->get($this->toParamsArray($index, $type, $id, false, $params));
    }

    /**
     * Retrieve a document's source
     *
     * @param        $index
     * @param        $id
     * @param string $type
     *
     * @return array|bool
     * @internal param \Determine\Module\Elastic\Contracts\DocumentLike $document
     */
    public function getSource($index, $id, $type = '_all')
    {
        return static::getClient()->getSource($this->toParamsArray($index, $type, $id, false));
    }

    /**
     * Perform a search using QSL
     *
     * @param array|string $index  The index
     * @param bool         $type   The doc type
     * @param array        $query  Query DSL
     * @param array        $params Any extra search parameters
     *
     * @return array
     */
    public function search($index, $type = null, $query = null, $params = [])
    {
        $_params = array_merge($params, ['index' => $index, 'body' => $query]);
        $this->adjustTypeParameter($_params, $type);

        $_response = static::getClient()->search($_params);

        $this->fireEvent('search',
            [
                'index'    => $index,
                'type'     => $type,
                'query'    => $query,
                'params'   => $params,
                'response' => $_response,
            ]);

        return $_response;
    }

    /**
     * Analog of search()
     *
     * @param array|string $index  The index
     * @param string       $type   The doc type
     * @param string       $query  Lucene format query string
     * @param array        $params Any extra search parameters
     *
     * @return array
     */
    public function query($index, $type = null, $query = null, $params = [])
    {
        return static::search($index, $type, $query, $params);
    }

    /**
     * @param string|DocumentLike $index
     * @param string|null         $type
     * @param string|null         $id
     *
     * @return array|bool The call's response or FALSE if not found
     */
    public function delete($index, $type = null, $id = null)
    {
        try {
            $_response = static::getClient()->delete($this->toParamsArray($index, $type, $id));

            $this->fireEvent('delete',
                [
                    'index'    => $index,
                    'type'     => $type,
                    'id'       => $id,
                    'response' => $_response,
                ]);

            return $_response;
        } catch (Missing404Exception $_ex) {
            return false;
        }
    }

    /**
     * @param string $index
     * @param array  $params
     *
     * @return bool TRUE if deleted. FALSE if not, or not found.
     */
    public function deleteIndex($index, $params = [])
    {
        try {
            $_response = static::getClient()->indices()->delete($this->toParamsArray($index, null, null, false, $params));

            $this->fireEvent('delete.index',
                [
                    'index'    => $index,
                    'params'   => $params,
                    'response' => $_response,
                ]);

            return $this->acked($_response);
        } catch (Missing404Exception $_ex) {
            return false;
        }
    }

    /**
     * Remaps an index based on the mapping defined in the document
     * This operation will close the index temporarily before applying the mapping
     *
     * @param DocumentLike $document
     * @param array        $params
     *
     * @return bool
     */
    public function indexRemap($document, $params = [])
    {
        $_client = static::getClient()->indices();
        $_index = $document->getIndex();
        $_params = array_merge($params, ['index' => $_index]);

        //  Create the index if it doesn't exist
        if (!$_client->exists($_params)) {
            $_client->create($_params);
        }

        if (null !== ($_map = $document->getMapping())) {
            $_map = [$document->getDocumentType() => $_map];
            $_params = array_merge($document->toParamsArray(false, false), $params);

            try {
                array_forget($_params, 'id');

                $_params['body'] = $_map;
                $_response = $_client->putMapping($_params);

                if (!$this->acked($_response)) {
                    $this->logWarning('[elastic.indexRemap] failed to put mapping', ['params' => array_except($_params, ['body'])]);

                    return false;
                }

                //  Close the index, put the setting and re-open
                $_result = $this->putSettings($_index, ['detect_language' => true]);

                return !$_result;
            } catch (\Exception $_ex) {
                $this->logError('[elastic.indexRemap] request failed: ' . $_ex->getMessage());
            }
        }

        return false;
    }

    /**
     * @param string $index  A comma-separated list of index names; use `_all` or empty string to perform the operation on all indices
     * @param array  $params Associative array of parameters
     *
     * @return array
     */
    public function getSettings($index, $params = [])
    {
        $_params = array_merge($params, ['index' => $index, 'flat_settings' => true]);
        $_response = static::getClient()->indices()->getSettings($_params);

        // $this->logDebug('[elastic.getSettings] response', ['response' => $_response]);

        return $_response;
    }

    /**
     * @param string $index    A comma-separated list of index names; use `_all` or empty string to perform the operation on all indices
     * @param array  $settings Hash of settings to put
     * @param array  $params   Associative array of parameters
     *
     * @return array
     */
    public function putSettings($index, $settings = [], $params = [])
    {
        $_client = static::getClient()->indices();
        $_response = $_client->close(array_merge($params, ['index' => $index]));

        if ($this->acked($_response)) {
            $_params = array_merge($params, ['index' => $index, 'body' => ['settings' => $settings], 'flat_settings' => true]);

            $_response = $_client->putSettings($_params);

            if (!$this->acked($_response)) {
                $this->logError('[elastic.putSettings] error putting settings', ['response' => $_response]);
            }

            //  Re-open
            $_response = $_client->open(array_merge($params, ['index' => $index]));

            if (!$this->acked($_response)) {
                $this->logError('[elastic.putSettings] unable to re-open index "' . $index . '"', ['response' => $_response]);
            }
        } else {
            $this->logError('[elastic.putSettings] unable to close index "' . $index . '"', ['response' => $_response]);
        }

        return $_response;
    }

    /**
     * @param string $index
     * @param string $id
     * @param string $type Defaults to "_all", which matches the first document of any type
     *
     * @return array|bool
     */
    public function exists($index, $id, $type = '_all')
    {
        $_response = static::getClient()->exists($this->toParamsArray($index, $type, $id, false));

        // $this->logDebug('[elastic.exists] response', ['response' => $_response]);

        return $_response;
    }

    /**
     * @param string $index
     *
     * @return array|bool
     */
    public function indexExists($index)
    {
        $_response = static::getClient()->indices()->exists($this->toParamsArray($index, null, null, false));

        // $this->logDebug('[elastic.exists] response', ['response' => $_response]);

        return $_response;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function info($params = [])
    {
        return static::getClient()->info($params);
    }

    /**
     * @param string $index
     * @param string $type
     * @param array  $params
     *
     * @return array
     */
    public function count($index, $type, $params = [])
    {
        return static::getClient()->count($this->toParamsArray($index, $type, null, false, $params));
    }

    /**
     * @param string $index The index
     * @param string $type  The document type
     * @param array  $params
     *
     * @return array
     */
    public function termVectors($index, $type, $params = [])
    {
        return static::getClient()->termvectors($this->toParamsArray($index, $type, null, false, $params));
    }

    /**
     * @param string $index
     * @param string $type
     * @param array  $params
     *
     * @return array
     */
    public function putMapping($index, $type, $params = [])
    {
        $_result = static::getClient()->indices()->putMapping($this->toParamsArray($index, $type, null, true, $params));

        return $_result;
    }

    /**
     * @param bool  $reload
     * @param array $config
     *
     * @return \Elasticsearch\Client
     */
    protected static function getClient($reload = false, $config = [])
    {
        if (empty($config)) {
            if (null !== ($_hosts = config('elastic.server.hosts'))) {
                $config = ['hosts' => $_hosts];
            }
        }

        return (null === static::$client || $reload) ? static::$client = ClientBuilder::fromConfig($config) : static::$client;
    }

    /**
     * Similar to one built into documents
     *
     * @param string      $index
     * @param string|null $type
     * @param string|null $id
     * @param bool        $refresh
     * @param array       $params
     *
     * @return array
     */
    protected function toParamsArray($index, $type = null, $id = null, $refresh = true, $params = [])
    {
        if ($index instanceof DocumentLike) {
            $_params = $index->toParamsArray(false, $refresh);
        } else {
            $_params = ['index' => $index];

            if ($refresh) {
                $_params['refresh'] = true;
            }

            if (null !== $type) {
                $_params['type'] = $type;
            }

            if (null !== $id) {
                $_params['id'] = $id;
            }
        }

        return array_merge($params, $_params);
    }

    /**
     * Tests a response array to see if the "acknowledged" bool is TRUE
     *
     * @param array $response The response
     *
     * @return bool
     */
    protected function acked($response)
    {
        return is_array($response) && array_get($response, 'acknowledged', false);
    }

    /**
     * @param array       $params The parameters to adjust
     * @param string|null $type   The type requested, if any
     */
    protected function adjustTypeParameter(&$params, $type = null)
    {
        if ($this->strictSearch) {
            if (!empty($type)) {
                $params['type'] = $type;
            }
        } else {
            array_forget($params, 'type');
        }
    }
}
