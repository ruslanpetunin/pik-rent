<?php

namespace app\components\apartment\models;

use app\components\apartment\interfaces\IApartmentModel;
use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\interfaces\IFilterValueFinder;
use app\components\filters_system\models\BuildingModel;
use app\models\active\Apartment;
use app\models\active\City;
use app\models\active\District;
use app\models\custom\ApiModel;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ApartmentModel
 *
 * Helps to work with apartments
 *
 * @package app\components\apartment\models
 */
class ApartmentModel extends Apartment implements IApartmentModel
{
    /** @var string SELECT_APARTMENTS_GETTING - it will show in api/search */
    const SELECT_APARTMENTS_GETTING = [
        'sCity' => 'c_name',
        'sDistrict' => 'd_name',
        'sAddress' => 'CONCAT(s_name, " д. ", b_house_number )',
        'sResidentialComplex' => 'rc_name',
        'iBuildingNumber' => 'b_building_number',
        'iMaxFloor' => 'b_floors_count',
        'iFloor' => 'a_floor',
        'iRoomsCount' => 'a_rooms_count',
        'iArea' => 'a_area',
        'iCost' => 'a_cost',
    ];

    /** @var ApiModel $oData */
    public $oData;

    /** @var BuildingModel $oBuildingModel */
    public $oBuildingModel;

    /**
     * Gets apartments by filter
     *
     * @param integer $iLimit
     * @param integer $iOffset
     * @return IApartmentModel[]
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function getByFilter($iLimit, $iOffset)
    {
        $oQuery = $this->getQueryByFilter();
        $aApartments = $oQuery->getChunkOfData($iLimit, $iOffset)->createCommand()->queryAll();
        $iCount = (int) $oQuery->count();

        if (!count($aApartments)) {
            if ($iOffset && $iCount) {
                $iLastPage = ceil($iCount / $iLimit);

                throw new NotFoundHttpException("No apartments found. Try to get another page. From 1 to $iLastPage");
            }

            throw new NotFoundHttpException('No apartments found by your filter');
        }

        return ['aData' => $this->convertIntValues($aApartments), 'iCount' => $iCount, 'iLimit' => $iLimit];
    }

    /**
     * Saves apartment with data
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function saveApartment()
    {
        $this->a_building_id = $this->oBuildingModel->getId($this->oData);
        $this->a_floor = $this->oData->getFloor();
        $this->a_cost = $this->oData->getCost();
        $this->a_rooms_count = $this->oData->getRoomsCount();
        $this->a_area = $this->oData->getArea();

        return $this->save();
    }

    /**
     * Builds main query for getting apartments by filter
     *
     * @return \app\models\queries\ApartmentQuery
     * @throws \yii\base\InvalidConfigException
     */
    protected function getQueryByFilter()
    {
        return static::find()->getByFilter($this->oData, self::SELECT_APARTMENTS_GETTING);
    }

    /**
     * Gets name columns of integer type
     *
     * @return string[]
     */
    protected function getIntColumnsFromSelect()
    {
        return array_values(
            array_filter(
                array_keys(self::SELECT_APARTMENTS_GETTING),
                function ($sKey) {
                    return mb_substr($sKey, 0, 1) === 'i';
                }
            )
        );
    }

    /**
     * Convert needed string params to int
     *
     * @param array $aApartments - array from selection SELECT_APARTMENTS_GETTING
     * @return mixed
     */
    protected function convertIntValues($aApartments)
    {
        $aIntColumn = $this->getIntColumnsFromSelect();

        for ($i = 0; $i < count($aApartments); $i++) {

            for ($j = 0; $j < count($aIntColumn); $j++) {
                $sKey = $aIntColumn[$j];
                $aApartments[$i][$sKey] = (int) $aApartments[$i][$sKey];
            }
        }

        return $aApartments;
    }
}