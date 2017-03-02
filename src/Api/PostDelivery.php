<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <gennadyx5@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Api;

use Gennadyx\ShopLogisticsRu\Response\Keys;

/**
 * PostDelivery remote api
 *
 * @method array add(array $deliveryData) Add post delivery
 * @method array getDeliveries(array $filter) Get post deliveries
 * @method array getDeliveriesByCode(array $codes) Get post deliveries by codes
 * @method array getTariffs(array $filter) Get post deliveries by filter
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class PostDelivery extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'add'                 => [
                'remote' => 'add_post_delivery',
                'keys'   => Keys::DELIVERIES,
            ],
            'getDeliveries'       => [
                'remote' => 'get_post_deliveries',
                'keys'   => Keys::DELIVERIES,
            ],
            'getDeliveriesByCode' => [
                'remote' => 'get_post_deliveries_array',
                'keys'   => Keys::DELIVERIES,
            ],
            'getTariffs'          => [
                'remote' => 'get_post_tariffs',
                'keys'   => Keys::ROOT,
            ],
        ];
    }
}
