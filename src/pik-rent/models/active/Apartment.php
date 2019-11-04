<?php

namespace app\models\active;

use Yii;

/**
 * This is the model class for table "apartments".
 *
 * @property int $a_id Identifier
 * @property int $a_building_id This one belongs building by id
 * @property int $a_area Area of this apartment in square meters
 * @property int $a_floor Floor number of this apartment
 * @property int $a_rooms_count Rooms count of this apartment
 * @property int $a_cost Worth of this apartment
 *
 * @property Buildings $aBuilding
 */
class Apartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apartments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['a_building_id', 'a_area', 'a_floor', 'a_rooms_count', 'a_cost'], 'required'],
            [['a_building_id', 'a_area', 'a_floor', 'a_rooms_count', 'a_cost'], 'integer'],
            [['a_building_id'], 'exist', 'skipOnError' => true, 'targetClass' => Building::className(), 'targetAttribute' => ['a_building_id' => 'b_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'a_building_id' => 'A Building ID',
            'a_area' => 'A Area',
            'a_floor' => 'A Floor',
            'a_rooms_count' => 'A Rooms Count',
            'a_cost' => 'A Cost',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\ApartmentQuery the newly created [[ActiveQuery]] instance.
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(\app\models\queries\ApartmentQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getABuilding()
    {
        return $this->hasOne(Building::className(), ['b_id' => 'a_building_id']);
    }
}
