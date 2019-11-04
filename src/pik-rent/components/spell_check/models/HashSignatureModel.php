<?php

namespace app\components\spell_check\models;

use app\components\spell_check\interfaces\IStrategyHelpRecord;
use app\models\active\HashSignatureWord;
use Yii;

class HashSignatureModel extends HashSignatureWord implements IStrategyHelpRecord
{

    /**
     * {@inheritdoc}
     * @return \app\models\queries\HashSignatureWordQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(\app\models\queries\HashSignatureWordQuery::className(), [get_called_class()]);
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
        $oHashSignatureWord = new static();
        $oHashSignatureWord->hsw_word_id = $iWordId;
        $oHashSignatureWord->hsw_hash = $aNeededData[0];

        return $oHashSignatureWord->save() ? $oHashSignatureWord->hsw_id : null;
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
            function ($oHashSignatureWord) {
                return $oHashSignatureWord->hswWord->d_word;
            },
            static::find()->wordsByHash($aValues[0])->all()
        );
    }
}