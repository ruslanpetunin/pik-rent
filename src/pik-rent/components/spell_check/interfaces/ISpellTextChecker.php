<?php

namespace app\components\spell_check\interfaces;

/**
 * Interface ISpellTextChecker
 *
 * Helps to fix errors in text
 *
 * @package app\components\spell_check\interfaces
 */
interface ISpellTextChecker
{
    
    /**
     * Fixes mistakes in a string
     *
     * @param string $sText - text
     * @return string
     */
    public function fixMistakes($sText);
}