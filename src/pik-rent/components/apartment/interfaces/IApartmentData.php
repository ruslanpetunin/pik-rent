<?php

namespace app\components\apartment\interfaces;

/**
 * Interface IApartmentData
 *
 * Helps to get value for searching from user's value
 *
 * @package app\filters_system\interfaces
 */
interface IApartmentData
{

    /**
     * Populates the model with input data.
     *
     * This method provides a convenient shortcut for:
     *
     * ```php
     * if (isset($_POST['FormName'])) {
     *     $model->attributes = $_POST['FormName'];
     *     if ($model->save()) {
     *         // handle success
     *     }
     * }
     * ```
     *
     * which, with `load()` can be written as:
     *
     * ```php
     * if ($model->load($_POST) && $model->save()) {
     *     // handle success
     * }
     * ```
     *
     * `load()` gets the `'FormName'` from the model's [[formName()]] method (which you may override), unless the
     * `$formName` parameter is given. If the form name is empty, `load()` populates the model with the whole of `$data`,
     * instead of `$data['FormName']`.
     *
     * Note, that the data being populated is subject to the safety check by [[setAttributes()]].
     *
     * @param array $data the data array to load, typically `$_POST` or `$_GET`.
     * @param string $formName the form name to use to load the data into the model.
     * If not set, [[formName()]] is used.
     * @return bool whether `load()` found the expected form in `$data`.
     */
    public function load($data, $formName = null);

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getCityId();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getDistrictId();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getStreetId();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getResidentialComplexId();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getBuildingNumber();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getHouseNumber();

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getFloorInterval();

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getRoomCountInterval();

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getAreaInterval();

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getMaxFloorInterval();

    /**
     * Gets value for building filter
     *
     * @return integer[]|null
     */
    public function getCostInterval();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getMaxFloor();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getFloor();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRoomsCount();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getArea();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getCost();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawCity();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawDistrict();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawStreet();

    /**
     * Gets value for building filter
     *
     * @return integer|null
     */
    public function getRawResidentialComplex();
}