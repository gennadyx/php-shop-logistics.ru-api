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

use Gennadyx\ShopLogisticsRu\ApiClientBuilder;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response\HtmlResponse;

class DeliveryTest extends TestCase
{
    public function testResponseKeyRoot()
    {
        $httpClient = new Client();
        $xml = <<<XML
<answer>
  <error>0</error>
  <price>173</price>
  <srok_dostavki>3</srok_dostavki>
  <error_code>0</error_code>
</answer>
XML;

        $httpClient->addResponse(new HtmlResponse($xml));
        $client = ApiClientBuilder::create()
            ->withHttpClient($httpClient)
            ->build();

        $answer = $client->delivery->getTimeAndPrice([]);

        $this->assertNotNull($answer);
        $this->assertArrayHasKey('price', $answer);
        $this->assertArrayHasKey('srok_dostavki', $answer);

        $this->assertEquals((float)$answer['price'], (float)173);
        $this->assertEquals((int)$answer['srok_dostavki'], 3);
    }
}
