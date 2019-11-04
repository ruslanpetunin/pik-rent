<?php

namespace app\components\spell_check\interfaces;

use yii\web\ServerErrorHttpException;

/**
 * Interface ISpellCheckerComponent
 *
 * Contains a methods for using outside
 *
 * @package app\components\spell_check\interfaces
 */
interface ISpellCheckerComponent
{

    /**
     * Fixes errors of string
     *
     * @param string $sString - text with errors
     * @return string
     * @throws ServerErrorHttpException
     */
    public function fixErrorsInString($sString);

    /**
     * Saves a word to dictionaries
     *
     * @param string $sWord - one word to remember
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function rememberWord($sWord);
}