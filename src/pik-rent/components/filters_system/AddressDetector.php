<?php

namespace app\components\filters_system;

use app\components\filters_system\interfaces\IAddressDetector;

/**
 * Class AddressDetector
 *
 * Helps to get street, building from string
 *
 * @package app\components\filters_system
 */
class AddressDetector implements IAddressDetector
{
    /**
     * Gets street, building from string
     *
     * @param string $sAddress
     * @return string[]
     */
    public function getParts($sAddress)
    {
        $aParts = array_map(
            function ($sPart) {
                return trim($sPart);
            },
            explode('д.', $sAddress)
        );

        return array_filter($aParts, function ($sPart) {return $sPart;});
    }
}