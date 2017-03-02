<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <gennadyx5@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu;

use MyCLabs\Enum\Enum;

/**
 * Enumeration for environment types
 *
 * @method static Environment TEST()
 * @method static Environment PROD()
 *
 * @author Gennady Knyazkin <gennadyx5@gmail.com>
 */
final class Environment extends Enum
{
    const TEST = 'test';
    const PROD = 'prod';
}
