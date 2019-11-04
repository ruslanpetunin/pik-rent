<?php

namespace app\components\filters_system\interfaces;

/**
 * Interface IAddressDetector
 *
 * Helps to get street, building from string
 *
 * @package app\filters_system\interfaces
 */
interface IAddressDetector
{

    /**
     * Gets city, district, street, building from string
     *
     * @param string $sAddress
     * @return string[]|null
     */
    public function getParts($sAddress);
}