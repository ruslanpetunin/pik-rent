<?php

namespace app\models\active;

use Yii;

/**
 * This is the model class for table "residential_complexes".
 *
 * @property int $rc_id Identifier
 * @property string $rc_name Name of residential complex
 * @property string $rc_hash_name For searching
 *
 * @property Buildings[] $buildings
 */
class ResidentialComplex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'residential_complexes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rc_name'], 'required'],
            [['rc_name'], 'string', 'max' => 255],
            [['rc_hash_name'], 'string', 'max' => 32],
            [['rc_hash_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rc_id' => 'Rc ID',
            'rc_name' => 'Rc Name',
            'rc_hash_name' => 'Rc Hash Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildings()
    {
        return $this->hasMany(Building::className(), ['b_residential_complex_id' => 'rc_id']);
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
                $this->rc_hash_name = $this->getHash($this->rc_name);
            }

            return true;
        }

        return false;
    }

    /**
     * Algorithm of hashing
     *
     * @param string $sWord - word
     * @return string
     */
    protected function getHash($sWord)
    {
        return md5($sWord);
    }
}
