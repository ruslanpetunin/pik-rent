<?php

namespace app\models\queries;

use app\components\apartment\interfaces\IApartmentData;
use app\components\apartment\models\Query;

/**
 * Class ApartmentQuery
 * @package app\models\queries
 */
class ApartmentQuery extends Query
{
    /** @var array $aConfigCondition - methods from IApartmentData will be called */
    protected $aConfigCondition = [
        ['getterName' => 'getCityId', 'searchingColumn' => 'd_city_id', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getDistrictId', 'searchingColumn' => 's_district_id', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getStreetId', 'searchingColumn' => 'b_street_id', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getResidentialComplexId', 'searchingColumn' => 'b_residential_complex_id', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getBuildingNumber', 'searchingColumn' => 'b_building_number', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getFloorInterval', 'searchingColumn' => 'a_floor', 'handleCondition' => 'getBetweenCondition'],
        ['getterName' => 'getRoomCountInterval', 'searchingColumn' => 'a_rooms_count', 'handleCondition' => 'getBetweenCondition'],
        ['getterName' => 'getAreaInterval', 'searchingColumn' => 'a_area', 'handleCondition' => 'getBetweenCondition'],
        ['getterName' => 'getMaxFloorInterval', 'searchingColumn' => 'b_floors_count', 'handleCondition' => 'getBetweenCondition'],
        ['getterName' => 'getCostInterval', 'searchingColumn' => 'a_cost', 'handleCondition' => 'getBetweenCondition'],
        ['getterName' => 'getFloor', 'searchingColumn' => 'a_floor', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getRoomsCount', 'searchingColumn' => 'a_rooms_count', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getArea', 'searchingColumn' => 'a_area', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getMaxFloor', 'searchingColumn' => 'b_floors_count', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getCost', 'searchingColumn' => 'a_cost', 'handleCondition' => 'getSimpleCondition'],
        ['getterName' => 'getHouseNumber', 'searchingColumn' => 'b_house_number', 'handleCondition' => 'getSimpleCondition'],
    ];

    /**
     * Gets apartments via filter
     *
     * @param IApartmentData $oFilter - object which has filter values
     * @param string $sSelect
     * @return ApartmentQuery
     */
    public function getByFilter(IApartmentData $oFilter, $sSelect)
    {
        return $this
            ->select($sSelect)
            ->leftJoin('buildings', 'a_building_id = b_id')
            ->leftJoin('residential_complexes', 'b_residential_complex_id = rc_id')
            ->leftJoin('streets', 'b_street_id = s_id')
            ->leftJoin('districts', 's_district_id = d_id')
            ->leftJoin('cities', 'd_city_id = c_id')
            ->joinCondition($oFilter);
    }
}