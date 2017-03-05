<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <dev@gennadyx.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Api;

use Gennadyx\ShopLogisticsRu\ApiClient;
use Gennadyx\ShopLogisticsRu\CallResult;
use Gennadyx\ShopLogisticsRu\Exception\BadMethodCallException;
use Gennadyx\ShopLogisticsRu\Response\Error;
use Gennadyx\ShopLogisticsRu\Response\Keys;
use Http\Client\Exception as HttpClientException;

/**
 * Abstract class of any api instance
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * @var ApiClient
     */
    protected $client;

    /**
     * AbstractApi constructor.
     *
     * @param ApiClient $client Api client instance
     */
    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array|Error
     * @throws \Gennadyx\ShopLogisticsRu\Exception\BadMethodCallException
     */
    public function __call($name, array $arguments)
    {
        $map = $this->getMethodsMap();

        if (!array_key_exists($name, $map)) {
            throw new BadMethodCallException(
                sprintf('Call to undefined method %s::%s()', static::class, $name)
            );
        }

        $method = $map[$name];

        if (is_string($method)) {
            $method = $map[$method];
        }

        $arguments = isset($method['arguments']) ? $method['arguments'] : $arguments;

        return $this->callMethod($method, $arguments);
    }

    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    abstract protected function getMethodsMap();

    /**
     * @param array $method Method info
     * @param array $args   Arguments
     *
     * @return array|Error
     */
    private function callMethod(array $method, array $args)
    {
        $callResult = $this->callRemote($method, $args);

        if ($callResult->isSuccess()) {
            return $callResult->getData();
        }

        return $callResult->getError();
    }

    /**
     * @param array $method    Method info
     * @param array $arguments Method arguments
     *
     * @return CallResult
     */
    private function callRemote(array $method, array $arguments)
    {
        $decode   = $this->client->getDecoder();
        $content  = $this->makeRequestXml($method['remote'], $arguments);
        $response = $decode($this->sendRequest($content));

        return $this->processResponse($response, $method['keys']);
    }

    /**
     * @param string $method Method name
     * @param array  $args   Method arguments
     *
     * @return string XML fore request
     */
    private function makeRequestXml($method, array $args)
    {
        $encode          = $this->client->getEncoder();
        $arrayForRequest = $this->buildRequestData($method, $args);

        return $encode($arrayForRequest);
    }

    /**
     * @param string $method Method name
     * @param array  $args   Method arguments
     *
     * @return array Array for convert to xml
     */
    private function buildRequestData($method, array $args)
    {
        $array = [
            'function' => $method,
            'api_id'   => $this->client->getKey(),
        ];

        return array_merge($array, $args);
    }

    /**
     * @param string $xml Request xml
     *
     * @return string|null
     */
    private function sendRequest($xml)
    {
        $requestFactory = $this->client->getRequestFactory();
        $httpClient     = $this->client->getHttpClient();

        $uri  = $this->client->getUrl();
        $data = urlencode(base64_encode($xml));

        $requestBody = sprintf('xml=%s', $data);
        $request     = $requestFactory->createRequest('POST', $uri, [], $requestBody);

        try {
            $request  = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
            $response = $httpClient->sendRequest($request);
            $result   = $response->getBody()->getContents();
        } catch (HttpClientException $e) {
            $result = null;
        } catch (\Exception $e) {
            $result = null;
        }

        return $result;
    }

    /**
     * @param array      $response     Response from api server
     * @param array|null $responseKeys Response root xml keys
     *
     * @return CallResult
     */
    private function processResponse(array $response = [], array $responseKeys = null)
    {
        $callResult = new CallResult();
        $errorCode  = Error::NO;

        if (isset($response['error'])) {
            $errorCode = (int) $response['error'];
            unset($response['error']);
        }

        $data = $this->buildResponseData($response, $responseKeys);

        if (null === $data) {
            $errorCode = Error::EMPTY_RESPONSE;
            $data      = [];
        }

        $callResult->setError($errorCode);
        $callResult->setData($data);

        return $callResult;
    }

    /**
     * @param array      $response
     * @param array|null $keys
     *
     * @return array|null
     */
    private function buildResponseData(array $response, array $keys = null)
    {
        if ($keys === Keys::ROOT) {
            return is_array($response) ? $response : [];
        }

        $list = $keys['list'];
        $item = $keys['item'];

        if (!isset($response[$list], $response[$list][$item])
            || empty($response[$list][$item])
        ) {
            return null;
        }

        $response = $response[$list][$item];

        if (!is_int(array_keys($response)[0])) {
            $response = [$response];
        }

        return $response;
    }
}
