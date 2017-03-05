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
 * ProductAct remote api
 *
 * @method array add(array $productActData) Add product act/acts
 * @method array update(array $productActData) Update product act/acts
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
final class ProductAct extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'add'    => [
                'remote' => 'client_add_update_products_act',
                'keys'   => Keys::PRODUCTS_ACTS,
            ],
            'update' => 'add',
        ];
    }
}
