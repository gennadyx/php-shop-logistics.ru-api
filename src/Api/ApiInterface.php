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

use Gennadyx\ShopLogisticsRu\Response\Error;

/**
 * Interface for all api classes
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
interface ApiInterface
{
    /**
     * Call remote method (function)
     *
     * @param string $name      Method name
     * @param array  $arguments Arguments
     *
     * @return array|Error
     */
    public function call($name, array $arguments);
}
