<?php

namespace app\components\spell_check\interfaces;

use yii\db\ActiveQuery;

/**
 * Interface IStrategyHelpRecord
 *
 * Help to work with a new word in a special dictionary table
 *
 * @package app\components\spell_check\interfaces
 */
interface IStrategyHelpRecord
{
    /**
     * Saves a new word to dictionary in db
     *
     * @param integer $iWordId - identifier of word from main dictionary table
     * @param array $aNeededData - needed data for saving in especial way
     * @return int|null - record id
     */
    public function saveNewWord($iWordId, ...$aNeededData);

    /**
     * Execute query to db for searching words
     *
     * @return string[]
     */
    public function getWordsBy(...$aValues);
}