<?php

namespace app\components\filters_system\models;

use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\interfaces\IFilterValueFinder;
use app\models\active\District;
use app\models\custom\ApiModel;
use Yii;

/**
 * Class DistrictModel
 *
 * Helps to find district id
 *
 * @package app\components\filters_system\models
 */
class DistrictModel extends District implements IFilterValueFinder
{

    /**
     * @param ApiModel $oData - data for finding
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getId($oData)
    {
        $oSpellChecker = Yii::$app->spellChecker;
        $sStringValue = $oData->getRawDistrict();

        if ($sStringValue) {
            $mDistrictName = $this->getHash(trim($oSpellChecker->fixErrorsInString($sStringValue)));
            $mDistrict = static::findOne(['d_hash_name' => $mDistrictName]);

            return $mDistrict ? $mDistrict->d_id : -1;
        }

        return -1;
    }
}