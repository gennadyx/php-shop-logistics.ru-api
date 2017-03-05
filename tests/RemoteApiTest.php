<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <gennadyx5@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Tests;

use Gennadyx\ShopLogisticsRu\ApiClient;
use Gennadyx\ShopLogisticsRu\ApiClientBuilder;
use Gennadyx\ShopLogisticsRu\Response\Error;
use Http\Client\Exception;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Gennadyx\ShopLogisticsRu\Exception\BadMethodCallException;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

class RemoteApiTest extends TestCase
{
    public function testDefinedMethodCalls()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();

        foreach (ApiClient::API as $api => $className) {
            $instance = $client->api($api);
            $methods  = $this->getMethodsMap($instance, $className);

            foreach ($methods as $method => $params) {
                $result = $instance->{$method}([]);
                $this->assertTrue($result instanceof Error || is_array($result));
            }
        }
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testUndefinedMethodCalls()
    {
        $client = ApiClientBuilder::create()
            ->withHttpClient(new Client())
            ->build();
        $client->delivery->callUndefinedMethod();
    }

    public function testSuccessCallMethodWithNotEmptyAnswer()
    {
        $httpClient = new Client();
        $xml = <<<XML
<answer>
  <error>0</error>
  <cities>
    <city>
      <name>"Мичуринец", Московская обл.</name>
      <code_id>882835</code_id>
      <is_courier>1</is_courier>
      <is_filial>0</is_filial>
      <oblast_code>000000001</oblast_code>
      <district_code/>
      <kladr_code>1603700004500</kladr_code>
    </city>
    <city>
      <name>19 Партсъезда, Волгоградская обл.</name>
      <code_id>778412</code_id>
      <is_courier>1</is_courier>
      <is_filial>0</is_filial>
      <oblast_code>000000022</oblast_code>
      <district_code/>
    </city>
    <city>
      <name>8 Марта, Башкортостан респ.</name>
      <code_id>856139</code_id>
      <is_courier>1</is_courier>
      <is_filial>0</is_filial>
      <oblast_code>000000049</oblast_code>
      <district_code/>
    </city>
    <city>
      <name>Tвepь, Москва</name>
      <code_id>272420</code_id>
      <is_courier>1</is_courier>
      <is_filial>0</is_filial>
      <oblast_code>000000092</oblast_code>
      <district_code/>
    </city>
</cities>
</answer>
XML;
        $response = new Response\HtmlResponse($xml);
        $httpClient->addResponse($response);
        $client = ApiClientBuilder::create()
            ->withHttpClient($httpClient)
            ->build();

        $answer = $client->dictionary->getCities();
        $this->assertTrue(is_array($answer));
        $this->assertCount(4, $answer);

        $city = $answer[0];
        $this->assertArrayHasKey('name', $city);
        $this->assertEquals('882835', $city['code_id']);
    }

    public function testSuccessCallMethodCreateListCollection()
    {
        $httpClient = new Client();
        $xml = <<<XML
<answer>
  <error>0</error>
  <cities>
    <city>
      <name>Tвepь, Москва</name>
      <code_id>272420</code_id>
      <is_courier>1</is_courier>
      <is_filial>0</is_filial>
      <oblast_code>000000092</oblast_code>
      <district_code/>
    </city>
</cities>
</answer>
XML;
        $response = new Response\HtmlResponse($xml);
        $httpClient->addResponse($response);
        $client = ApiClientBuilder::create()
            ->withHttpClient($httpClient)
            ->build();

        $answer = $client->dictionary->getCities();
        $this->assertTrue(is_array($answer));
        $this->assertCount(1, $answer);
    }

    public function testHttpException()
    {
        $httpClient = new Client();
        $httpClient->addException(new Exception\HttpException('test', new Request(), new Response\EmptyResponse()));
        $client = ApiClientBuilder::create()
            ->withHttpClient($httpClient)
            ->build();
        $this->assertInstanceOf(Error::class, $client->dictionary->getAffiliate());
    }

    public function testException()
    {
        $httpClient = new Client();
        $httpClient->addException(new \Exception());
        $client = ApiClientBuilder::create()
            ->withHttpClient($httpClient)
            ->build();
        $this->assertInstanceOf(Error::class, $client->dictionary->getAffiliate());
    }

    protected function getMethodsMap($instance, $className)
    {
        $reflectionMethod = (new \ReflectionClass($className))->getMethod('getMethodsMap');
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($instance);
    }
}
