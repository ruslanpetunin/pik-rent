<?php

namespace app\models\active;

use app\components\filters_system\traits\HashHelper;
use Yii;

/**
 * This is the model class for table "streets".
 *
 * @property int $s_id Identifier
 * @property string $s_name Name of street
 * @property int $s_district_id Referrer to district
 * @property string $s_hash_name For searching
 *
 * @property Address[] $addresses
 * @property District $sDistrict
 */
class Street extends \yii\db\ActiveRecord
{
    use HashHelper;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'streets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['s_name', 's_district_id'], 'required'],
            [['s_district_id'], 'integer'],
            [['s_name'], 'string', 'max' => 255],
            [['s_hash_name'], 'string', 'max' => 32],
            [['s_hash_name'], 'unique'],
            [['s_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['s_district_id' => 'd_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'S ID',
            's_name' => 'S Name',
            's_district_id' => 'S District ID',
            's_hash_name' => 'S Hash Name',
        ];
    }

    /**
     * Executes before saving
     *
     * @param bool $isInsert - flag
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($isInsert)
    {
        if (parent::beforeSave($isInsert)) {

            if ($this->isNewRecord) {
                $this->s_hash_name = $this->getHash($this->s_name);
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['a_street_id' => 's_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSDistrict()
    {
        return $this->hasOne(District::className(), ['d_id' => 's_district_id']);
    }
}
