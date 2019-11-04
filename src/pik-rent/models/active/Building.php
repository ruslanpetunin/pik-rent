<?php

namespace app\models\active;

use Yii;

/**
 * This is the model class for table "buildings".
 *
 * @property int $b_id Identifier
 * @property int $b_residential_complex_id This one belongs residential complex by id
 * @property int $b_street_id For searching address
 * @property int $b_house_number House number on the street
 * @property int $b_apartments_count Count apartments of this building
 * @property int $b_floors_count Count floors of this building
 * @property int $b_building_number Building number on the street
 *
 * @property Apartments[] $apartments
 * @property ResidentialComplexes $bResidentialComplex
 * @property Streets $bStreet
 */
class Building extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buildings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['b_residential_complex_id', 'b_street_id', 'b_house_number', 'b_building_number', 'b_apartments_count', 'b_floors_count'], 'required'],
            [['b_residential_complex_id', 'b_street_id', 'b_house_number', 'b_building_number', 'b_apartments_count', 'b_floors_count'], 'integer'],
            [['b_street_id', 'b_house_number', 'b_building_number'], 'unique', 'targetAttribute' => ['b_street_id', 'b_house_number']],
            [['b_residential_complex_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResidentialComplex::className(), 'targetAttribute' => ['b_residential_complex_id' => 'rc_id']],
            [['b_street_id'], 'exist', 'skipOnError' => true, 'targetClass' => Street::className(), 'targetAttribute' => ['b_street_id' => 's_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'b_id' => 'B ID',
            'b_residential_complex_id' => 'B Residential Complex ID',
            'b_street_id' => 'B Street ID',
            'b_house_number' => 'B House Number',
            'b_building_number' => 'B Building Number',
            'b_apartments_count' => 'B Apartments Count',
            'b_floors_count' => 'B Floors Count',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\BuildingQuery the newly created [[ActiveQuery]] instance.
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(\app\models\queries\BuildingQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApartments()
    {
        return $this->hasMany(Apartment::className(), ['a_building_id' => 'b_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBResidentialComplex()
    {
        return $this->hasOne(ResidentialComplex::className(), ['rc_id' => 'b_residential_complex_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBStreet()
    {
        return $this->hasOne(Street::className(), ['s_id' => 'b_street_id']);
    }
}
