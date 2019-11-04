<?php

namespace app\models\active;

/**
 * This is the model class for table "two_gram_words".
 *
 * @property int $tgw_id Identifier
 * @property int $tgw_gram_id Part of word in a dictionary
 * @property int $tgw_word_id Referrer to word
 *
 * @property TwoGrams $tgwGram
 * @property Dictionary $tgwWord
 */
class TwoGramWord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'two_gram_words';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgw_gram_id', 'tgw_word_id'], 'required'],
            [['tgw_gram_id', 'tgw_word_id'], 'integer'],
            [['tgw_gram_id', 'tgw_word_id'], 'unique', 'targetAttribute' => ['tgw_gram_id', 'tgw_word_id']],
            [['tgw_gram_id'], 'exist', 'skipOnError' => true, 'targetClass' => TwoGram::className(), 'targetAttribute' => ['tgw_gram_id' => 'tg_id']],
            [['tgw_word_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dictionary::className(), 'targetAttribute' => ['tgw_word_id' => 'd_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tgw_id' => 'Tgw ID',
            'tgw_gram_id' => 'Tgw Gram ID',
            'tgw_word_id' => 'Tgw Word ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTgwGram()
    {
        return $this->hasOne(TwoGram::className(), ['tg_id' => 'tgw_gram_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTgwWord()
    {
        return $this->hasOne(Dictionary::className(), ['d_id' => 'tgw_word_id']);
    }
}
