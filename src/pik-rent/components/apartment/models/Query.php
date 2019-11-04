<?php

namespace app\components\apartment\models;

use app\components\apartment\interfaces\IApartmentData;
use app\models\queries\ApartmentQuery;
use yii\db\ActiveQuery;

/**
 * Class Query
 *
 * @package app\components\apartment\models
 */
class Query extends ActiveQuery
{
    /** @var array $aConfigCondition - methods from IApartmentData will be called */
    protected $aConfigCondition = [
// for example
//        ['getterName' => 'getHouseNumber', 'searchingColumn' => 'b_house_number', 'handleCondition' => 'getSimpleCondition'],
    ];

    /**
     * Adds limit and offset
     *
     * @param integer $iLimit
     * @param integer $iOffset
     * @return Query
     */
    public function getChunkOfData($iLimit, $iOffset)
    {
        return $this->offset($iOffset)->limit($iLimit);
    }

    /**
     * Collects conditions
     *
     * @param IApartmentData $oFilter - object which has filter values
     * @return Query
     */
    protected function joinCondition(IApartmentData $oFilter)
    {
        $oQuery = $this;
        $isWhere = true;

        for ($i = 0; $i < count($this->aConfigCondition); $i++) {
            $aConfig = $this->aConfigCondition[$i];
            $sGettingValueMethod = $aConfig['getterName'];
            $sGettingConditionMethod = $aConfig['handleCondition'];
            $mValueFilter = $oFilter->$sGettingValueMethod();
            $mCondition = !is_null($mValueFilter)
                ? $this->$sGettingConditionMethod($aConfig['searchingColumn'], $mValueFilter)
                : $mValueFilter;

            if ($mCondition) {
                $oQuery = $isWhere ? $oQuery->where($mCondition) : $oQuery->andWhere($mCondition);
                $isWhere = false;
            }
        }

        return $oQuery;
    }

    /**
     * Make a condition
     *
     * @param string $sColumnName
     * @param integer[]|string|integer $mValue
     * @return array
     */
    protected function getBetweenCondition($sColumnName, $mValue)
    {
        $aCondition = ['between', $sColumnName];
        $aCondition[] = $mValue[0];
        $aCondition[] = $mValue[1];

        return $aCondition;
    }

    /**
     * Make a condition
     *
     * @param string $sColumnName
     * @param integer[]|string|integer $mValue
     * @return array
     */
    protected function getSimpleCondition($sColumnName, $mValue)
    {
        return [$sColumnName => $mValue];
    }
}