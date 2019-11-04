<?php

namespace app\components\filters_system\interfaces;

use app\components\apartment\interfaces\IApartmentData;

/**
 * Interface IFilterValueFinder
 *
 * Helps to find id of word
 *
 * @package app\components\filters_system\interfaces
 */
interface IFilterValueFinder
{

    /**
     * @param IApartmentData $oData - data for finding
     * @return mixed
     */
    public function getId($oData);
}