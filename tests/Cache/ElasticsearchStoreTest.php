<?php namespace ChaoticWave\SilentMovie\Tests\Cache;

use ChaoticWave\SilentMovie\Cache\ElasticsearchStore;
use ChaoticWave\SilentMovie\Tests\TestCase;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class CacheElasticsearchStoreTest extends TestCase
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $client;
    /**
     * @var ElasticsearchStore
     */
    protected $store;

    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(Client::class)) {
            $this->markTestSkipped('Elasticsearch SDK not installed');
        }

        $this->client = ClientBuilder::fromConfig(['hosts' => config('cache.stores.elasticsearch.hosts', [])]);
        $this->store = new ElasticsearchStore($this->client, 'foo');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->store = $this->client = null;
    }

    public function testGetReturnsNullWhenNotFound()
    {
        $this->assertNull($this->store->get('bar'));
    }

    public function testElasticsearchValueIsReturned()
    {
        $this->store->put('foo', 'bar', 1);
        $this->assertEquals('bar', $this->store->get('foo'));
    }

    public function testElasticsearchGetMultiValuesAreReturnedWithCorrectKeys()
    {
        $this->assertEquals([
            'foo' => 'fizz',
            'bar' => 'buzz',
            'baz' => 'norf',
        ],
            $this->store->many([
                'foo',
                'bar',
                'baz',
            ]));
    }

    public function testSetMethodProperlyCallsElasticsearch()
    {
        \Carbon\Carbon::setTestNow($now = \Carbon\Carbon::now());
        $this->store->put('foo', 'bar', 1);
        \Carbon\Carbon::setTestNow();
    }

    public function testIncrementMethodProperlyCallsElasticsearch()
    {
        $this->store->increment('foo', 5);
    }

    public function testDecrementMethodProperlyCallsElasticsearch()
    {
        $this->store->decrement('foo', 5);
    }

    public function testStoreItemForeverProperlyCallsElasticsearch()
    {
        $this->store->forever('foo', 'bar');
    }

    public function testForgetMethodProperlyCallsElasticsearch()
    {
        $this->store->forget('foo');
    }

    public function testFlushesCached()
    {
        $result = $this->store->flush();
        $this->assertTrue($result);
    }

    public function testGetAndSetPrefix()
    {
        $this->assertEquals('bar:', $this->store->getPrefix());
        $$this->store->setPrefix('foo');
        $this->assertEquals('foo:', $this->store->getPrefix());
        $$this->store->setPrefix(null);
        $this->assertEmpty($$this->store->getPrefix());
    }
}
