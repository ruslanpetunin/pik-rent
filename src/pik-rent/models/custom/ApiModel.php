<?php

namespace app\models\custom;

use app\components\filters_system\AddressDetector;
use app\components\apartment\interfaces\IApartmentData;
use app\components\filters_system\models\CityModel;
use app\components\filters_system\models\DistrictModel;
use app\components\filters_system\models\ResidentialComplexModel;
use app\components\filters_system\models\StreetModel;
use app\components\filters_system\traits\HashHelper;
use Yii;
use yii\base\Model;

/**
 * Class ApiModel
 *
 * Contains filter data from ApiController actionSearch
 *
 * @package app\models\custom
 */
class ApiModel extends Model implements IApartmentData
{

    /** @var string SCENARIO_ADD */
    const SCENARIO_ADD = 'scenario save';

    /** @var string|null $sCity */
    public $sCity;

    /** @var string|null $sDistrict */
    public $sDistrict;

    /** @var string|null $sAddress */
    public $sAddress;

    /** @var string|null $sResidentialComplex */
    public $sResidentialComplex;

    /** @var integer|null $iBuildingNumber */
    public $iBuildingNumber;

    /** @var integer|null $iMaxFloor */
    public $iMaxFloor;

    /** @var integer|null $iMaxFloorFrom */
    public $iMaxFloorFrom;

    /** @var integer|null $iMaxFloorTo */
    public $iMaxFloorTo;

    /** @var integer|null $iFloor */
    public $iFloor;

    /** @var integer|null $iFloorFrom */
    public $iFloorFrom;

    /** @var integer|null $iFloorTo */
    public $iFloorTo;

    /** @var integer|null $iRoomsCount */
    public $iRoomsCount;

    /** @var integer|null $iRoomsCountFrom */
    public $iRoomsCountFrom;

    /** @var integer|null $iRoomsCountTo */
    public $iRoomsCountTo;

    /** @var integer|null $iArea */
    public $iArea;

    /** @var integer|null $iAreaFrom */
    public $iAreaFrom;

    /** @var integer|null $iAreaTo */
    public $iAreaTo;

    /** @var integer|null $iCost */
    public $iCost;

    /** @var integer|null $iCostFrom */
    public $iCostFrom;

    /** @var integer|null $iCostTo */
    public $iCostTo;

    /** @var AddressDetector $oAddressDetector */
    public $oAddressDetector;

    /** @var CityModel $oCityModel */
    public $oCityModel;

    /** @var DistrictModel $oDistrictModel */
    public $oDistrictModel;

    /** @var StreetModel $oStreetModel */
    public $oStreetModel;

    /** @var ResidentialComplexModel $oResidentialComplexModel */
    public $oResidentialComplexModel;

    /**
     * Validating rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'sCity', 'sDistrict', 'sAddress', 'sResidentialComplex', 'iBuildingNumber',
                    'iMaxFloor', 'iFloor', 'iRoomsCount', 'iArea', 'iCost',
                ], 'required', 'on' => self::SCENARIO_ADD
            ],
            [
                [
                    'iBuildingNumber', 'iMaxFloorFrom', 'iMaxFloorTo', 'iFloorFrom', 'iFloorTo',
                    'iRoomsCountFrom', 'iRoomsCountTo', 'iAreaFrom', 'iAreaTo', 'iCostFrom', 'iCostTo',
                    'iMaxFloor', 'iFloor', 'iRoomsCount', 'iArea', 'iCost',
                ], 'integer'
            ],
            [['sCity', 'sDistrict', 'sAddress', 'sResidentialComplex'], 'string', 'max' => 255],
            [['sAddress'], 'addressValidation'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $aScenarios = parent::scenarios();
        $aScenarios[self::SCENARIO_ADD] = [
            'sCity', 'sDistrict', 'sAddress', 'sResidentialComplex', 'iBuildingNumber',
            'iMaxFloor', 'iFloor', 'iRoomsCount', 'iArea', 'iCost',
        ];

        return $aScenarios;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getCityId()
    {
        return $this->sCity ? $this->oCityModel->getId($this) : null;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getDistrictId()
    {
        return $this->sDistrict ? $this->oDistrictModel->getId($this) : null;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getStreetId()
    {
        $mAddress = $this->getRawStreet();

        return $mAddress ? $this->oStreetModel->getId($this) : null;
    }

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getFloorInterval()
    {
        return $this->fillIntervalDefault($this->iFloorFrom, $this->iFloorTo);
    }

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getRoomCountInterval()
    {
        return $this->fillIntervalDefault($this->iRoomsCountFrom, $this->iRoomsCountTo);
    }

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getCostInterval()
    {
        return $this->fillIntervalDefault($this->iCostFrom, $this->iCostTo);
    }

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getAreaInterval()
    {
        return $this->fillIntervalDefault($this->iAreaFrom, $this->iAreaTo);
    }

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getMaxFloorInterval()
    {
        return $this->fillIntervalDefault($this->iMaxFloorFrom, $this->iMaxFloorTo);
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getResidentialComplexId()
    {
        return $this->sResidentialComplex ? $this->oResidentialComplexModel->getId($this) : null;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getBuildingNumber()
    {
        return $this->iBuildingNumber;
    }

    /**
     * If one of params is null it would be filled default values
     *
     * @param integer|null $mMin
     * @param integer|null $mMax
     * @return integer[]
     */
    protected function fillIntervalDefault($mMin, $mMax)
    {
        $iMin = $mMin ?: 0;
        $iMax = $mMax ?: 99999999999;

        return [$iMin, $iMax];
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getHouseNumber()
    {
        $mAddress = $this->sAddress ? $this->oAddressDetector->getParts($this->sAddress) : null;

        return $mAddress && count($mAddress) === 2 ? $mAddress[1] : null;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getMaxFloor()
    {
        return $this->iMaxFloor;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getFloor()
    {
        return $this->iFloor;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRoomsCount()
    {
        return $this->iRoomsCount;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getArea()
    {
        return $this->iArea;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getCost()
    {
        return $this->iCost;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawCity()
    {
        return $this->sCity;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawDistrict()
    {
        return $this->sDistrict;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawResidentialComplex()
    {
        return $this->sResidentialComplex;
    }

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawStreet()
    {
        return $this->sAddress ? $this->oAddressDetector->getParts($this->sAddress) : null;
    }

    /**
     * Validator
     *
     * @param $sKey
     */
    public function addressValidation($sKey)
    {
        if (count($this->getRawStreet()) !== 2) {
            $this->addError($sKey, 'Address must be a format like `Улица такая д. 42`');
        }
    }
}