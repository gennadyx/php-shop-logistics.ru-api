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

use Gennadyx\ShopLogisticsRu\CallResult;
use Gennadyx\ShopLogisticsRu\Response\Error;
use PHPUnit\Framework\TestCase;

class CallResultTest extends TestCase
{
    public function testUnknownErrorSet()
    {
        $callResult = new CallResult(3224432432);
        $this->assertTrue(Error::UNKNOWN_ERROR()->equals($callResult->getError()));
    }

    public function testIfEmptyDataOrHasErrorIsSuccessFalse()
    {
        $callResult1 = new CallResult(Error::NO, []);
        $this->assertFalse($callResult1->isSuccess());

        $callResult2 = new CallResult(Error::UNKNOWN_ERROR, ['test' => 'test1']);
        $this->assertFalse($callResult2->isSuccess());
    }

    public function testIsSuccessTrue()
    {
        $callResult = new CallResult(Error::NO, ['test1' => 'test2']);
        $this->assertTrue($callResult->isSuccess());
    }
}
