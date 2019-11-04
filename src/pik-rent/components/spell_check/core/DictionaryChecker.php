<?php

namespace app\components\spell_check\core;

use app\components\spell_check\interfaces\IDictionaryChecker;
use app\components\spell_check\interfaces\SpellCheckerStrategy;
use app\components\spell_check\strategies\HashSignatureStrategy;
use app\components\spell_check\strategies\TwoGramStrategy;
use app\models\queries\TwoGramWordQuery;

/**
 * Class DictionaryChecker
 *
 * Helps to fix errors in a word
 *
 * @package app\components\spell_check\core
 */
class DictionaryChecker implements IDictionaryChecker
{

    /** @var HashSignatureStrategy|TwoGramStrategy $oSpellCheckStrategy - finding and saving strategy*/
    protected $oSpellCheckStrategy;

    /**
     * DictionaryChecker constructor.
     *
     * @param SpellCheckerStrategy $oSpellCheckStrategy
     */
    public function __construct(SpellCheckerStrategy $oSpellCheckStrategy)
    {
        $this->oSpellCheckStrategy = $oSpellCheckStrategy;
    }

    /**
     * Fixes error in an any word
     *
     * @param string $sWord - word with mistake
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getRightWord($sWord)
    {
        $mResultWord = null;
        $iMaxLevenshteinDistance = mb_strlen($sWord);
        $aSimilarWords = $this->oSpellCheckStrategy->find($sWord);
        $iMinLevenshteinDistance = 100;

        for ($i = 0; $i < count($aSimilarWords); $i++) {
            $iLevenshteinDistance = levenshtein($aSimilarWords[$i], $sWord);

            if ($iMinLevenshteinDistance >= $iLevenshteinDistance && $iLevenshteinDistance < $iMaxLevenshteinDistance) {
                $iMinLevenshteinDistance = $iLevenshteinDistance;
                $mResultWord = $aSimilarWords[$i];
            }
        }

        return $mResultWord;
    }

    /**
     * Saves a word to dictionary
     *
     * @param string $sWord - saving word
     * @return mixed
     */
    public function saveRightWord($sWord)
    {
        return $this->oSpellCheckStrategy->save($sWord);
    }
}