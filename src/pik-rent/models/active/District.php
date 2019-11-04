<?php

namespace app\models\active;

use app\components\filters_system\traits\HashHelper;
use Yii;

/**
 * This is the model class for table "districts".
 *
 * @property int $d_id Identifier
 * @property string $d_name Name of district
 * @property int $d_city_id Referrer to the city
 * @property string $d_hash_name For searching
 *
 * @property City $dCity
 */
class District extends \yii\db\ActiveRecord
{
    use HashHelper;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'districts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['d_name', 'd_city_id'], 'required'],
            [['d_city_id'], 'integer'],
            [['d_name'], 'string', 'max' => 255],
            [['d_hash_name'], 'string', 'max' => 32],
            [['d_hash_name'], 'unique'],
            [['d_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['d_city_id' => 'c_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'd_id' => 'D ID',
            'd_name' => 'D Name',
            'd_city_id' => 'D City ID',
            'd_hash_name' => 'D Hash Name',
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
                $this->d_hash_name = $this->getHash($this->d_name);
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDCity()
    {
        return $this->hasOne(City::className(), ['c_id' => 'd_city_id']);
    }
}
