<?php

namespace app\components\spell_check\interfaces;

/**
 * Interface IDictionaryRecord
 *
 * Help to work with a new word in a main dictionary table
 *
 * @package app\components\spell_check\interfaces
 */
interface IDictionaryRecord
{
    /**
     * Saves a new word to dictionary in db
     *
     * @param string $sWord - saving word
     * @return int|null - record id
     */
    public function saveNewWord($sWord);
}