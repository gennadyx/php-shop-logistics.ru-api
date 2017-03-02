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
 * Products remote api
 *
 * @method array add(array $productsData) Add product/products
 * @method array update(array $productsData) Update product/products
 * @method array getProducts(array $filter) Get products by filter
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class Products extends AbstractApi
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
                'remote' => 'client_add_update_products',
                'keys'   => Keys::PRODUCTS,
            ],
            'update'      => 'add',
            'getProducts' => [
                'remote' => 'client_get_products',
                'keys'   => Keys::PRODUCTS,
            ],
        ];
    }
}
