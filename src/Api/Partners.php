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

use Gennadyx\ShopLogisticsRu\Response\Keys;

/**
 * Partners remote api
 *
 * @method array getPartners(int $fromCity, int $toCity) Get partners by from cities
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
final class Partners extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'getPartners' => [
                'remote' => 'get_all_couriers_partners',
                'keys'   => Keys::PARTNERS,
            ],
        ];
    }
}
