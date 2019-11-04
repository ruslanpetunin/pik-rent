<?php

namespace app\components\filters_system\traits;

use Yii;

/**
 * Trait HashHelper
 * 
 * @package app\components\filters_system\traits
 */
trait HashHelper
{

    /**
     * Algorithm of hashing
     *
     * @param string $sWord - word
     * @return string
     */
    protected function getHash($sWord)
    {
        return md5($sWord);
    }

}