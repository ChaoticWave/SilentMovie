<?php namespace ChaoticWave\SilentMovie\Tests\Cache;

use ChaoticWave\SilentMovie\Tests\TestCase;
use Elasticsearch\ClientBuilder;

class ElasticsearchConnectorTest extends TestCase
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = ClientBuilder::fromConfig(['hosts' => config('cache.stores.elasticsearch.hosts', [])]);
    }

    public function testServer()
    {
        $this->assertTrue($this->client->ping());
    }

    public function tearDown()
    {
        $this->client = null;
    }
}