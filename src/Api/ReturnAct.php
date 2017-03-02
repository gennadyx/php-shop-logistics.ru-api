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
 * ReturnAct remote api
 *
 * @method array getList(string $dateAdded, string $actNumber, string $status) Get return acts
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class ReturnAct extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    protected function getMethodsMap()
    {
        return [
            'getList' => [
                'remote' => 'get_act_vozvrata',
                'keys'   => Keys::DOCUMENTS,
            ],
        ];
    }
}
