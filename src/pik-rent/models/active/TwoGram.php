<?php

namespace app\models\active;

/**
 * This is the model class for table "two_grams".
 *
 * @property int $tg_id Identifier
 * @property string $tg_gram Part of word in a dictionary
 *
 * @property TwoGramWord[] $twoGramWords
 * @property Dictionary[] $tgwWords
 */
class TwoGram extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'two_grams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tg_gram'], 'required'],
            [['tg_gram'], 'string', 'max' => 2],
            [['tg_gram'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tg_id' => 'Tg ID',
            'tg_gram' => 'Tg Gram',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTwoGramWords()
    {
        return $this->hasMany(TwoGramWord::className(), ['tgw_gram_id' => 'tg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTgwWords()
    {
        return $this->hasMany(Dictionary::className(), ['d_id' => 'tgw_word_id'])->viaTable('two_gram_words', ['tgw_gram_id' => 'tg_id']);
    }
}
