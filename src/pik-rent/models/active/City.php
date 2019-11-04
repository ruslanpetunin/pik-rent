<?php

namespace app\models\active;

use app\components\filters_system\traits\HashHelper;
use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property int $c_id Identifier
 * @property string $c_name Name of city
 * @property string $c_hash_name Key for searching
 */
class City extends \yii\db\ActiveRecord
{
    use HashHelper;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_name'], 'required'],
            [['c_name'], 'string', 'max' => 255],
            [['c_hash_name'], 'string', 'max' => 32],
            [['c_hash_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'c_name' => 'C Name',
            'c_hash_name' => 'C Hash Name',
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
                $this->c_hash_name = $this->getHash($this->c_name);
            }

            return true;
        }

        return false;
    }
}
