<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <dev@gennadyx.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Tests;

use Gennadyx\ShopLogisticsRu\Api\Delivery;
use Gennadyx\ShopLogisticsRu\Api\Dictionary;
use Gennadyx\ShopLogisticsRu\Api\Partners;
use Gennadyx\ShopLogisticsRu\Api\Pickup;
use Gennadyx\ShopLogisticsRu\Api\PostDelivery;
use Gennadyx\ShopLogisticsRu\Api\ProductAct;
use Gennadyx\ShopLogisticsRu\Api\Products;
use Gennadyx\ShopLogisticsRu\Api\QaReports;
use Gennadyx\ShopLogisticsRu\Api\ReturnAct;
use Gennadyx\ShopLogisticsRu\ApiClient;
use Gennadyx\ShopLogisticsRu\ApiClientBuilder;
use Gennadyx\ShopLogisticsRu\Environment;
use Gennadyx\ShopLogisticsRu\Exception\InvalidArgumentException;
use Gennadyx\ShopLogisticsRu\Exception\RuntimeException;
use Http\Client\Curl\Client as CurlClient;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;

/**
 * Test ApiClientTest
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
class ApiClientTest extends TestCase
{
    /**
     * Function testConstructorDefaultParams
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorDefaultParams()
    {
        $client = ApiClientBuilder::create()->build();
        $this->assertEquals(ApiClient::TEST_KEY, $client->getKey());
        $this->assertEquals(ApiClient::URL[Environment::TEST], $client->getUri());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetUndefinedApi()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();
        $client->api('undefined.api');
    }

    public function testApiInstanceOf()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();

        foreach (ApiClient::API as $api => $className) {
            $this->assertInstanceOf($className, $client->api($api));
        }
    }

    public function testPropertyGetter()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();

        $this->assertInstanceOf(Delivery::class, $client->delivery);
        $this->assertInstanceOf(Dictionary::class, $client->dictionary);
        $this->assertInstanceOf(Partners::class, $client->partners);
        $this->assertInstanceOf(Pickup::class, $client->pickup);
        $this->assertInstanceOf(PostDelivery::class, $client->postDelivery);
        $this->assertInstanceOf(ProductAct::class, $client->productAct);
        $this->assertInstanceOf(Products::class, $client->products);
        $this->assertInstanceOf(QaReports::class, $client->qaReports);
        $this->assertInstanceOf(ReturnAct::class, $client->returnAct);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUndefinedProperty()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();
        $this->assertNull($client->invalidApi);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetPropertyException()
    {
        $client           = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();
        $client->delivery = new Delivery($client);
    }


    public function testIssetMagicMethod()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();
        $this->assertTrue(isset($client->delivery));
    }

    public function testApiClientBuilder()
    {
        $key    = 'test_api_key';
        $client = ApiClientBuilder::create()
            ->withKey($key)
            ->withHttpClient(new Client())
            ->withEnvironment(Environment::PROD())
            ->withRequestFactory(new GuzzleMessageFactory())
            ->withStreamFactory(new GuzzleStreamFactory())
            ->withEncoder(
                function ($data) {
                    return 'encode';
                }
            )
            ->withDecoder(
                function ($data) {
                    return 'decode';
                }
            )
            ->build();

        $this->assertEquals($key, $client->getKey());
        $this->assertInstanceOf(Client::class, $client->getHttpClient());
        $this->assertEquals(ApiClient::URL[Environment::PROD], $client->getUri());
        $this->assertInstanceOf(GuzzleMessageFactory::class, $client->getRequestFactory());
        $this->assertInstanceOf(GuzzleStreamFactory::class, $client->getStreamFactory());
        $this->assertTrue(is_callable($client->getEncoder()));
        $this->assertTrue(is_callable($client->getDecoder()));

        $encode = $client->getEncoder();
        $decode = $client->getDecoder();
        $this->assertEquals('encode', $encode(''));
        $this->assertEquals('decode', $decode(''));
    }

    public function testApiClientBuilderWithDefault()
    {
        $client = ApiClientBuilder::create()->build();

        $this->assertEquals(ApiClient::TEST_KEY, $client->getKey());
        $this->assertInstanceOf(CurlClient::class, $client->getHttpClient());
        $this->assertEquals(ApiClient::URL[Environment::TEST], $client->getUri());
        $this->assertInstanceOf(DiactorosMessageFactory::class, $client->getRequestFactory());
        $this->assertInstanceOf(DiactorosStreamFactory::class, $client->getStreamFactory());

        $encode = $client->getEncoder();
        $decode = $client->getDecoder();

        $xml = <<<XML
<?xml version="1.0"?>
<request><sub><item1><key>Test1</key></item1><item2><key>Test2</key></item2></sub></request>

XML;

        $array = [
            'sub' => [
                'item1' => [
                    'key' => 'Test1',
                ],
                'item2' => [
                    'key' => 'Test2',
                ],
            ],
        ];

        $this->assertEquals($array, $decode($xml));
        $this->assertEquals($xml, $encode($array));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorInvalidKeyArgument()
    {
        $callback = function () {};
        new ApiClient(
            new DiactorosMessageFactory(),
            new DiactorosStreamFactory(),
            new Client(),
            $callback,
            $callback,
            1234,
            Environment::TEST()
        );
    }
}
