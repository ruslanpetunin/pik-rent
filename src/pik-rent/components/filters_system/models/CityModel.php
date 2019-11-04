<?php

namespace app\components\filters_system\models;

use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\interfaces\IFilterValueFinder;
use app\models\active\City;
use app\models\custom\ApiModel;
use Yii;

/**
 * Class CityModel
 *
 * Helps to find city id
 *
 * @package app\components\filters_system\models
 */
class CityModel extends City implements IFilterValueFinder
{

    /**
     * @param ApiModel $oData - data for finding
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getId($oData)
    {
        $oSpellChecker = Yii::$app->spellChecker;
        $sStringValue = $oData->getRawCity();

        if ($sStringValue) {
            $mCityName = $this->getHash(trim($oSpellChecker->fixErrorsInString($sStringValue)));
            $mCity = static::findOne(['c_hash_name' => $mCityName]);

            return $mCity ? $mCity->c_id : -1;
        }

        return -1;
    }
}