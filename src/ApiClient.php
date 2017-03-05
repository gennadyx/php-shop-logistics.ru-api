<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <dev@gennadyx.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu;

use Gennadyx\ShopLogisticsRu\Api\ApiInterface;
use Gennadyx\ShopLogisticsRu\Api\Delivery;
use Gennadyx\ShopLogisticsRu\Api\Dictionary;
use Gennadyx\ShopLogisticsRu\Api\Partners;
use Gennadyx\ShopLogisticsRu\Api\Pickup;
use Gennadyx\ShopLogisticsRu\Api\PostDelivery;
use Gennadyx\ShopLogisticsRu\Api\ProductAct;
use Gennadyx\ShopLogisticsRu\Api\Products;
use Gennadyx\ShopLogisticsRu\Api\QaReports;
use Gennadyx\ShopLogisticsRu\Api\ReturnAct;
use Gennadyx\ShopLogisticsRu\Exception\InvalidArgumentException;
use Gennadyx\ShopLogisticsRu\Exception\RuntimeException;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use ReflectionClass;

/**
 * Api client for work with shop-logistics.ru api
 *
 *
 * @property Delivery     $delivery      Delivery api instance
 * @property Dictionary   $dictionary    Dictionary api instance
 * @property PostDelivery $postDelivery  MailDelivery api instance
 * @property Partners     $partners      Partners api instance
 * @property Pickup       $pickup        Pickup api instance
 * @property Products     $products      Products api instance
 * @property ProductAct   $productAct    Products api instance
 * @property QaReports    $qaReports     QaReports api instance
 * @property ReturnAct    $returnAct     ReturnAct api instance
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
class ApiClient
{
    /**
     * Api key for testing
     */
    const TEST_KEY = '577888574a3e4df01867cd5ccc9f18a5';

    /**
     * Urls for request
     */
    const URL = [
        Environment::TEST => 'https://test.client-shop-logistics.ru/index.php?route=deliveries/api',
        Environment::PROD => 'http://client-shop-logistics.ru/index.php?route=deliveries/api',
    ];

    /**
     * Available api
     */
    const API = [
        'delivery'      => Delivery::class,
        'dictionary'    => Dictionary::class,
        'post.delivery' => PostDelivery::class,
        'partners'      => Partners::class,
        'pickup'        => Pickup::class,
        'products'      => Products::class,
        'product.act'   => ProductAct::class,
        'qa.reports'    => QaReports::class,
        'return.act'    => ReturnAct::class,
    ];

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @var string
     */
    private $key;

    /**
     * @var ApiInterface[]
     */
    private $instances;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var callable
     */
    private $encoder;

    /**
     * @var callable
     */
    private $decoder;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * ApiClient constructor.
     *
     * @param RequestFactory $requestFactory    Instance of RequestFactory (psr-7)
     * @param StreamFactory  $streamFactory     Instance of StreamFactory (psr-7)
     * @param HttpClient     $httpClient        Http client instance
     * @param callable       $encoder           Callback for encoding array to xml (without cdata).
     *                                          Get array argument and return xml string.
     * @param callable       $decoder           Callback for decoding xml to array (without cdata).
     *                                          Get xml string argument and return array.
     * @param string         $key               Api key
     * @param Environment    $environment       Environment (Test or production)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        RequestFactory $requestFactory,
        StreamFactory $streamFactory,
        HttpClient $httpClient,
        callable $encoder,
        callable $decoder,
        $key,
        Environment $environment
    ) {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Api key must be string, %s given',
                    is_object($key) ? get_class($key) : gettype($key)
                )
            );
        }

        $this->key            = $key;
        $this->uri            = self::URL[$environment->getValue()];
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
        $this->encoder        = $encoder;
        $this->decoder        = $decoder;
        $this->httpClient     = $httpClient;
    }

    /**
     * Get api key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get encoder
     *
     * @return callable
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Get decoder
     *
     * @return callable
     */
    public function getDecoder()
    {
        return $this->decoder;
    }

    /**
     * Get httpClient
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get requestFactory
     *
     * @return RequestFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * Get streamFactory
     *
     * @return StreamFactory
     */
    public function getStreamFactory()
    {
        return $this->streamFactory;
    }

    /**
     * @param string $name
     *
     * @return ApiInterface
     * @throws Exception\RuntimeException
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        return $this->api(
            strtolower(preg_replace('/([a-z]+)([A-Z]+)/', '$1.$2', $name))
        );
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, self::API);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws RuntimeException
     */
    public function __set($name, $value)
    {
        throw new RuntimeException('ApiClient instance is immutable.');
    }

    /**
     * Return api class instance by name
     *
     * @param string $api Api name
     *
     * @return ApiInterface
     * @throws \Gennadyx\ShopLogisticsRu\Exception\RuntimeException
     * @throws \Gennadyx\ShopLogisticsRu\Exception\InvalidArgumentException
     */
    public function api($api)
    {
        if (!array_key_exists($api, self::API)) {
            throw new InvalidArgumentException(
                sprintf('Api "%s" not found', $api)
            );
        }

        return $this->getApiInstance($api);
    }

    /**
     * @param string $api Api name
     *
     * @return ApiInterface
     * @throws InvalidArgumentException
     */
    private function getApiInstance($api)
    {
        $className = self::API[$api];

        if (null === $this->instances
            || !isset($this->instances[$api])
        ) {
            $this->instances[$api] = $this->createApiInstance($className);
        }

        return $this->instances[$api];
    }

    /**
     * @param string $className
     *
     * @return ApiInterface
     * @throws InvalidArgumentException
     */
    private function createApiInstance($className)
    {
        $reflectionClass = new ReflectionClass($className);
        $newInstance     = $reflectionClass->newInstance($this);

        if (!($newInstance instanceof ApiInterface)) {
            throw new InvalidArgumentException(
                sprintf('Api object must be a implement ApiInterface. But "%s" not implement it.', $className)
            );
        }

        return $newInstance;
    }
}
