<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <dev@gennadyx.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Response;

/**
 * List of response xml root keys
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
final class Keys
{
    const ROOT                 = null;
    const DELIVERIES           = [
        'list' => 'deliveries',
        'item' => 'delivery',
    ];
    const TARIFFS              = [
        'list' => 'tarifs',
        'item' => 'tarif',
    ];
    const PARTNERS             = [
        'list' => 'partners',
        'item' => 'partner',
    ];
    const PICKUPS              = [
        'list' => 'zabors',
        'item' => 'zabor',
    ];
    const PLACES               = [
        'list' => 'zabor_places',
        'item' => 'zabor_place',
    ];
    const PRODUCTS_ACTS        = [
        'list' => 'products_acts',
        'item' => 'products_act',
    ];
    const PRODUCTS             = [
        'list' => 'products',
        'item' => 'product',
    ];
    const DOCUMENTS            = [
        'list' => 'documents',
        'item' => 'document',
    ];
    const DICTIONARY_CITIES    = [
        'list' => 'cities',
        'item' => 'city',
    ];
    const DICTIONARY_METRO     = [
        'list' => 'metros',
        'item' => 'metro',
    ];
    const DICTIONARY_PICKUPS   = [
        'list' => 'pickups',
        'item' => 'pickup',
    ];
    const DICTIONARY_AFFILIATE = [
        'list' => 'filials',
        'item' => 'filial',
    ];
    const DICTIONARY_STATUSES  = [
        'list' => 'status_list',
        'item' => 'status',
    ];
    const DICTIONARY_PARTNERS  = [
        'list' => 'partners',
        'item' => 'partner',
    ];
    const DICTIONARY_STATES    = [
        'list' => 'oblast_list',
        'item' => 'oblast',
    ];
    const DICTIONARY_DISTRICTS = [
        'list' => 'district_list',
        'item' => 'district',
    ];
    const SITES                = [
        'list' => 'sitename',
        'item' => 'site',
    ];
}
