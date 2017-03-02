<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <gennadyx5@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu;

use Http\Client\Curl\Client;
use Http\Client\HttpClient;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * ApiClient builder.
 *
 * Building new instance of ApiClient with parameters:
 *  - requestFactory: Default is \Http\Message\MessageFactory\DiactorosMessageFactory
 *  - streamFactory: Default is \Http\Message\StreamFactory\DiactorosStreamFactory
 *  - httpClient: Defalt is \Http\Client\Curl\Client
 *  - encoder: Default is closure (using XmlEncoder from symfony serializer component)
 *  - decoder: Default is closure (using XmlEncoder from symfony serializer component)
 *  - key: Default is ApiClient::TEST_KEY
 *  - environment: Default is Environment::TEST()
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
class ApiClientBuilder
{
    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var callable
     */
    protected $encoder;

    /**
     * @var callable
     */
    protected $decoder;

    /**
     * Create builder
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Build api client instance
     *
     * @return ApiClient
     * @throws \Gennadyx\ShopLogisticsRu\Exception\InvalidArgumentException
     */
    public function build()
    {
        $fields = array_keys(get_class_vars(static::class));

        foreach ($fields as $field) {
            if (null === $this->{$field}) {
                $this->{sprintf('default%s', ucfirst($field))}();
            }
        }

        $apiClient = new ApiClient(
            $this->requestFactory,
            $this->streamFactory,
            $this->httpClient,
            $this->encoder,
            $this->decoder,
            $this->key,
            $this->environment
        );

        return $apiClient;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function withKey($key)
    {
        $this->key = (string) $key;

        return $this;
    }

    /**
     * @param Environment $environment
     *
     * @return $this
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * @param RequestFactory $requestFactory
     *
     * @return $this
     */
    public function withRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * @param StreamFactory $streamFactory
     *
     * @return $this
     */
    public function withStreamFactory(StreamFactory $streamFactory)
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return $this
     */
    public function withHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param callable $encoder
     *
     * @return $this
     */
    public function withEncoder(callable $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * @param callable $decoder
     *
     * @return $this
     */
    public function withDecoder(callable $decoder)
    {
        $this->decoder = $decoder;

        return $this;
    }

    protected function defaultRequestFactory()
    {
        $this->requestFactory = new DiactorosMessageFactory();
    }

    protected function defaultStreamFactory()
    {
        $this->streamFactory = new DiactorosStreamFactory();
    }

    protected function defaultHttpClient()
    {
        $this->httpClient = new Client($this->requestFactory, $this->streamFactory);
    }

    protected function defaultEncoder()
    {
        $this->encoder = function (array $data) {
            try {
                $xml = (new XmlEncoder('request'))->encode($data, 'xml');
            } catch (UnexpectedValueException $e) {
                $xml = '';
            }

            return $xml;
        };
    }

    protected function defaultDecoder()
    {
        $this->decoder = function ($data) {
            try {
                $data = (new XmlEncoder('request'))->decode($data, 'xml');
            } catch (UnexpectedValueException $e) {
                $data = [];
            }

            return $data;
        };
    }

    protected function defaultKey()
    {
        $this->key = ApiClient::TEST_KEY;
    }

    protected function defaultEnvironment()
    {
        $this->environment = Environment::TEST();
    }
}
