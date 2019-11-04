<?php

namespace app\models\active;

use app\components\filters_system\traits\HashHelper;
use app\components\spell_check\interfaces\IDictionaryRecord;
use Yii;

/**
 * This is the model class for table "dictionary".
 *
 * @property int $d_id Identifier
 * @property string $d_word One word
 * @property string $d_hash md5 hash of word
 *
 * @property HashSignatureWords[] $hashSignatureWords
 * @property TwoGramWords[] $twoGramWords
 */
class Dictionary extends \yii\db\ActiveRecord implements IDictionaryRecord
{
    use HashHelper;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dictionary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['d_word'], 'required'],
            [['d_word'], 'string', 'max' => 255],
            [['d_hash'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'd_id' => 'D ID',
            'd_word' => 'D Word',
            'd_hash' => 'D Hash',
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
                $this->d_hash = $this->getHash($this->d_word);
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHashSignatureWord()
    {
        return $this->hasMany(HashSignatureWord::className(), ['hsw_word_id' => 'd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTwoGramWord()
    {
        return $this->hasMany(TwoGramWord::className(), ['tgw_word_id' => 'd_id']);
    }

    /**
     * Saves a new word to dictionary in db
     *
     * @param string $sWord - saving word
     * @return int|null - record id
     */
    public function saveNewWord($sWord)
    {
        $oDictionary = static::findOne(['d_hash' => $this->getHash($sWord)]) ?: new static();
        $oDictionary->d_word = $sWord;

        return $oDictionary->save() ? $oDictionary->d_id : null;
    }
}
