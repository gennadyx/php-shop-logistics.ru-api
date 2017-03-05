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
 * Dictionary remote api
 *
 * @method array getCities() Get cities
 * @method array getMetro() Get metro
 * @method array getPickups() Get pickups
 * @method array getAffiliate() Get affiliate
 * @method array getPartners() Get partners
 * @method array getStates() Get states
 * @method array getDistricts() Get districts
 * @method array getStatuses() Get statuses
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
final class Dictionary extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'getCities'    => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_CITIES,
                'arguments' => ['dictionary_type' => 'city'],
            ],
            'getMetro'     => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_METRO,
                'arguments' => ['dictionary_type' => 'metro'],
            ],
            'getPickups'   => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_PICKUPS,
                'arguments' => ['dictionary_type' => 'pickup'],
            ],
            'getAffiliate' => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_AFFILIATE,
                'arguments' => ['dictionary_type' => 'filials'],
            ],
            'getPartners'  => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_PARTNERS,
                'arguments' => ['dictionary_type' => 'partners'],
            ],
            'getStates'    => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_STATES,
                'arguments' => ['dictionary_type' => 'oblast'],
            ],
            'getDistricts' => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_DISTRICTS,
                'arguments' => ['dictionary_type' => 'district'],
            ],
            'getStatuses'  => [
                'remote'    => 'get_dictionary',
                'keys'      => Keys::DICTIONARY_STATUSES,
                'arguments' => ['dictionary_type' => 'status'],
            ],
        ];
    }
}
