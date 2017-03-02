<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <gennadyx5@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu\Response;

use MyCLabs\Enum\Enum;

/**
 * Enumeration for response errors
 *
 * @method static Error NO()
 * @method static Error CLIENT_NOT_FOUND()
 * @method static Error UNDEFINED_FUNCTION()
 * @method static Error ORDER_NOT_FOUND()
 * @method static Error EMPTY_RESPONSE()
 * @method static Error UNKNOWN_ERROR()
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class Error extends Enum
{
    const NO                 = 0;
    const CLIENT_NOT_FOUND   = 1;
    const UNDEFINED_FUNCTION = 2;
    const ORDER_NOT_FOUND    = 3;
    const EMPTY_RESPONSE     = 9876;
    const UNKNOWN_ERROR      = 7895;

    /**
     * Error constructor
     *
     * @param int $errorCode
     */
    public function __construct($errorCode)
    {
        try {
            parent::__construct($errorCode);
        } catch (\UnexpectedValueException $e) {
            $this->value = self::UNKNOWN_ERROR;
        }
    }
}
