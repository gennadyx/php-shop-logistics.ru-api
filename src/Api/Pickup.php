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
 * Pickup remote api
 *
 * @method array add(int $place, string $date) Add new pickup
 * @method array addPlace(array $placeData, string &$errorMessage) Add new
 *         pickup place
 * @method array updatePlace(string $code, array $placeData, string &$errorMessage) Update pickup place
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class Pickup extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'add'         => [
                'remote' => 'add_zabor',
                'keys'   => Keys::PICKUPS,
            ],
            'addPlace'    => [
                'remote' => 'client_add_update_zabor_place',
                'keys'   => Keys::PLACES,
            ],
            'updatePlace' => 'addPlace',
        ];
    }
}
