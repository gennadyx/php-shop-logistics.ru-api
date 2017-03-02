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
 * QaReports remote api
 *
 * @method array getList(string $dateAdded, string $actNumber, string $status)
 *         Get qa reports by filter
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class QaReports extends AbstractApi
{
    /**
     * Get all available methods with remote functions
     *
     * @return array
     */
    protected function getMethodsMap()
    {
        return [
            'getList' => [
                'remote' => 'get_qa_reports',
                'keys'   => Keys::DOCUMENTS,
            ],
        ];
    }
}
