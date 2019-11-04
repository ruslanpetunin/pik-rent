<?php

namespace app\components\filters_system\models;

use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\interfaces\IFilterValueFinder;
use app\models\active\Street;
use app\models\custom\ApiModel;
use Yii;

/**
 * Class StreetModel
 *
 * Helps to find street id
 *
 * @package app\components\filters_system\models
 */
class StreetModel extends Street implements IFilterValueFinder
{

    /**
     * @param ApiModel $oData - data for finding
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getId($oData)
    {
        $oSpellChecker = Yii::$app->spellChecker;
        $sStringValue = $oData->getRawStreet()[0];

        if ($sStringValue) {
            $mStreetName = $this->getHash(trim($oSpellChecker->fixErrorsInString($sStringValue)));
            $mStreet = static::findOne(['s_hash_name' => $mStreetName]);

            return $mStreet ? $mStreet->s_id : -1;
        }

        return -1;
    }
}