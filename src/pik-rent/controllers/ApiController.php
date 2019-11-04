<?php

namespace app\controllers;

use app\components\apartment\models\ApartmentModel;
use app\components\filters_system\AddressDetector;
use app\components\filters_system\models\BuildingModel;
use app\components\filters_system\models\CityModel;
use app\components\filters_system\models\DistrictModel;
use app\components\filters_system\models\ResidentialComplexModel;
use app\components\filters_system\models\StreetModel;
use app\models\custom\ApiModel;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ApiController
 * @package app\controllers
 */
class ApiController extends Controller
{
    /** @var string ERROR_INCORRECT_ADDRESS - bad request */
    const ERROR_INCORRECT_ADDRESS = 'Your city, district, street, residential complex and building are not connected with each other';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'search'  => ['GET'],
                    'add'  => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $sPhpInput = file_get_contents('php://input');

        \Yii::info(json_encode(['GET' => $_GET, 'POST' => $sPhpInput]), 'api_controller');

        return parent::beforeAction($action);
    }

    /**
     * It is GET endpoint
     * You may use here params:
     *
     * sCity, sDistrict, sAddress, sResidentialComplex - string type
     *
     * iBuildingNumber,
     * iMaxFloor, iMaxFloorFrom, iMaxFloorTo,
     * iFloor, iFloorFrom, iFloorTo,
     * iRoomsCount, iRoomsCountFrom, iRoomsCountTo,
     * iArea, iAreaFrom, iAreaTo,
     * iCost, iCostFrom, iCostTo - integer type
     *
     * iPage - integer too
     *
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionSearch()
    {
        $oRequest = Yii::$app->request;
        $isValidPage = is_numeric($oRequest->get('iPage'));
        $iPage = $isValidPage ? $oRequest->get('iPage') : 1;
        $iLimit = 100;
        $iOffset = $iLimit * ($iPage - 1);
        $oFilter = $this->instApiModel();

        if (count($oRequest->get()) && (!$oFilter->load($oRequest->get(), '') || !$oFilter->validate())) {
            throw new BadRequestHttpException(json_encode($oFilter->getErrors()));
        }

        return $this->instApartmentModel($oFilter)->getByFilter($iLimit, $iOffset);
    }

    /**
     * It is POST endpoint
     * You must use here params:
     *
     * sCity, sDistrict, sAddress, sResidentialComplex - string type
     * iBuildingNumber, iMaxFloor, iFloor, iRoomsCount, iArea, iCost, - integer type
     *
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionAdd()
    {
        $oRequest = Yii::$app->request;
        $oFilter = $this->instApiModel(ApiModel::SCENARIO_ADD);

        if (!$oFilter->load($oRequest->post(), '') || !$oFilter->validate()) {
            throw new BadRequestHttpException(json_encode($oFilter->getErrors()));
        }

        $oApartmentModel = $this->instApartmentModel($oFilter);

        if (!$oApartmentModel->saveApartment()) {
            throw new BadRequestHttpException(self::ERROR_INCORRECT_ADDRESS);
        }

        return [];
    }

    /**
     * Makes object
     *
     * @param string $sScenario
     * @return ApiModel
     * @throws \yii\base\InvalidConfigException
     */
    protected function instApiModel($sScenario = ApiModel::SCENARIO_DEFAULT)
    {
        return Yii::createObject(ApiModel::className(), [[
            'scenario' => $sScenario, 'oAddressDetector' => new AddressDetector(),
            'oCityModel' => new CityModel(), 'oDistrictModel' => new DistrictModel(),
            'oStreetModel' => new StreetModel(), 'oResidentialComplexModel' => new ResidentialComplexModel(),
        ]]);
    }

    /**
     * Makes object
     *
     * @param ApiModel $oFilter
     * @return ApartmentModel
     * @throws \yii\base\InvalidConfigException
     */
    protected function instApartmentModel(ApiModel $oFilter)
    {
        return Yii::createObject(ApartmentModel::className(), [
            ['oData' => $oFilter, 'oBuildingModel' => new BuildingModel()]
        ]);
    }
}