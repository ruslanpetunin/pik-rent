<?php

namespace app\components\apartment\interfaces;

/**
 * Interface IApartmentModel
 *
 * Helps to work with apartments
 *
 * @package app\components\filters_system\interfaces
 */
interface IApartmentModel
{

    /**
     * Saves apartment with data
     *
     * @return integer|null
     */
    public function saveApartment();

    /**
     * Gets apartments by filter
     *
     * @param integer $iLimit
     * @param integer $iOffset
     * @return IApartmentModel[]
     */
    public function getByFilter($iLimit, $iOffset);
}