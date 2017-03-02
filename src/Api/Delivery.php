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
 * Delivery remote api
 *
 * @method array add(array $deliveryData) Add new delivery/deliveries and return result
 * @method array update(array $deliveryData) Update existing delivery/deliveries and return result
 * @method array getBy(array $filter) Get deliveries list by filter
 * @method array getStatus(string $deliveryCode) Get delivery status by code
 * @method array getStatusList(array $deliveryCodes) Get delivery statuses list by array of codes
 * @method array getTimeAndPrice(array $parameters) Get time and price for delivery
 * @method array getVariants(array $parameters) Get delivery variants by parameters
 * @method array getTariffs(array $parameters) Get tariffs for delivery by parameters
 * @method array updateSiteName(array $parameters) Update sitename
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class Delivery extends AbstractApi
{
    /**
     * Get all available methods with remote functions and response xml root keys
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'add'             => [
                'remote' => 'add_delivery',
                'keys'   => Keys::DELIVERIES,
            ],
            'update'          => 'add',
            'getBy'           => [
                'remote' => 'get_deliveries',
                'keys'   => Keys::DELIVERIES,
            ],
            'getStatus'       => [
                'remote' => 'get_order_status',
                'keys'   => Keys::DELIVERIES,
            ],
            'getStatusList'   => [
                'remote' => 'get_order_status_array',
                'keys'   => Keys::DELIVERIES,
            ],
            'getTimeAndPrice' => [
                'remote' => 'get_delivery_price',
                'keys'   => Keys::ROOT,
            ],
            'getVariants'     => [
                'remote' => 'get_deliveries_tarifs',
                'keys'   => Keys::TARIFFS,
            ],
            'getTariffs'      => [
                'remote' => 'get_all_tarifs',
                'keys'   => Keys::TARIFFS,
            ],
            'updateSiteName'  => [
                'remote' => 'add_update_sitename',
                'keys'   => Keys::SITES,
            ],
        ];
    }
}
