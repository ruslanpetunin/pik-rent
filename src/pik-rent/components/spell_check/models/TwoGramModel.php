<?php

namespace app\components\spell_check\models;

use app\components\spell_check\interfaces\IStrategyHelpRecord;
use app\models\active\TwoGram;
use app\models\active\TwoGramWord;
use Yii;

class TwoGramModel extends TwoGramWord implements IStrategyHelpRecord
{

    /**
     * {@inheritdoc}
     * @return \app\models\queries\TwoGramWordQuery the newly created [[ActiveQuery]] instance.
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(\app\models\queries\TwoGramWordQuery::className(), [get_called_class()]);
    }

    /**
     * Saves a new word to dictionary in db
     *
     * @param integer $iWordId - identifier of word from main dictionary table
     * @param array $aNeededData - needed data for saving in especial way
     * @return int|null - record id
     */
    public function saveNewWord($iWordId, ...$aNeededData)
    {
        $oTwoGramWord = new static();
        $oTwoGramWord->tgw_gram_id = $oTwoGramWord->saveTwoGram($aNeededData[0]);
        $oTwoGramWord->tgw_word_id = $iWordId;

        return $oTwoGramWord->save() ? $oTwoGramWord->tgw_id : null;
    }

    /**
     * Execute query to db for searching words
     *
     * @return string[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getWordsBy(...$aValues)
    {
        return array_map(
            function ($oTwoGramWord) {
                return $oTwoGramWord->tgwWord->d_word;
            },
            static::find()->wordsByGrams($aValues)->all()
        );
    }

    /**
     * Saves in gram table
     *
     * @param string $sTwoGram - part of word
     * @return int|null
     */
    protected function saveTwoGram($sTwoGram)
    {
        $oTwoGram = TwoGram::findOne($sTwoGram) ?: new TwoGram();
        $oTwoGram->tg_gram = $sTwoGram;

        return $oTwoGram->save() ? $oTwoGram->tg_id : null;
    }
}