<?php

namespace app\components\spell_check\interfaces;

/**
 * Interface IDictionaryChecker
 *
 * Helps to fix errors in a word
 *
 * @package app\components\spell_check\interfaces
 */
interface IDictionaryChecker
{

    /**
     * Fixes error in an any word
     *
     * @param string $sWord - word with mistake
     * @return string|null
     */
    public function getRightWord($sWord);

    /**
     * Saves a word to dictionary
     *
     * @param string $sWord - saving word
     * @return bool
     */
    public function saveRightWord($sWord);
}