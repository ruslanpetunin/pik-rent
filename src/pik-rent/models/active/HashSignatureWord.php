<?php

namespace app\models\active;

use Yii;

/**
 * This is the model class for table "hash_signature_words".
 *
 * @property int $hsw_id Identifier
 * @property string $hsw_hash Hash signature of word
 * @property int $hsw_word_id Referrer to word
 *
 * @property Dictionary $hswWord
 */
class HashSignatureWord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hash_signature_words';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hsw_hash', 'hsw_word_id'], 'required'],
            [['hsw_word_id'], 'integer'],
            [['hsw_hash'], 'string', 'max' => 13],
            [['hsw_word_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dictionary::className(), 'targetAttribute' => ['hsw_word_id' => 'd_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hsw_id' => 'Hsw ID',
            'hsw_hash' => 'Hsw Hash',
            'hsw_word_id' => 'Hsw Word ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHswWord()
    {
        return $this->hasOne(Dictionary::className(), ['d_id' => 'hsw_word_id']);
    }
}
