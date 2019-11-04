<?php

namespace app\components\filters_system\models;

use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\interfaces\IFilterValueFinder;
use app\models\active\ResidentialComplex;
use app\models\custom\ApiModel;
use Yii;

/**
 * Class ResidentialComplexModel
 *
 * Helps to find residential complex id
 *
 * @package app\components\filters_system\models
 */
class ResidentialComplexModel extends ResidentialComplex implements IFilterValueFinder
{

    /**
     * @param ApiModel $oData - data for finding
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getId($oData)
    {
        $oSpellChecker = Yii::$app->spellChecker;
        $sStringValue = $oData->getRawResidentialComplex();

        if ($sStringValue) {
            $mResidentialComplexName = $this->getHash(trim($oSpellChecker->fixErrorsInString($sStringValue)));
            $mResidentialComplex = static::findOne(['rc_hash_name' => $mResidentialComplexName]);

            return $mResidentialComplex ? $mResidentialComplex->rc_id : -1;
        }

        return -1;
    }
}