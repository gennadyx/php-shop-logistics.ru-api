<?php

/*
 * This file is part of the php-shop-logistics.ru-api package.
 *
 * (c) Gennady Knyazkin <dev@gennadyx.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gennadyx\ShopLogisticsRu;

use Gennadyx\ShopLogisticsRu\Response\Error;

/**
 * Helper for work with results calling of remote functions
 *
 * @author Gennady Knyazkin <dev@gennadyx.tech>
 */
final class CallResult
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var Error
     */
    protected $error;

    /**
     * CallResult constructor.
     *
     * @param int        $errorCode
     * @param array|null $data
     */
    public function __construct($errorCode = Error::NO, array $data = [])
    {
        $this->data = $data;
        $this->setError($errorCode);
    }

    /**
     * Return is success current call of remote function
     *
     * @return bool
     */
    public function isSuccess()
    {
        return !empty($this->data) && Error::NO()->equals($this->error);
    }

    /**
     * Return remote function response as array
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get error
     *
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error
     *
     * @param int $errorCode
     *
     * @return $this
     */
    public function setError($errorCode)
    {
        $this->error = new Error($errorCode);

        return $this;
    }
}
