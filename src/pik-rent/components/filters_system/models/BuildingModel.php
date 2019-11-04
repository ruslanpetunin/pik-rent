<?php

namespace app\components\filters_system\models;

use app\components\filters_system\interfaces\IFilterValueFinder;
use app\models\active\Building;
use app\models\custom\ApiModel;

/**
 * Class BuildingModel
 * @package app\components\filters_system\models
 */
class BuildingModel extends Building implements IFilterValueFinder
{

    /**
     * @param ApiModel $oData - data for finding
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getId($oData)
    {
        $oBuilding = static::find()->getByFilter($oData)->one();

        return $oBuilding ? $oBuilding->b_id : null;
    }
}