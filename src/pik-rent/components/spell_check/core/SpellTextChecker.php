<?php

namespace app\components\spell_check\core;

use app\components\spell_check\interfaces\IDictionaryChecker;
use app\components\spell_check\interfaces\ISpellTextChecker;

/**
 * Class SpellTextChecker
 *
 * Fixes mistakes in a string
 *
 * @package app\components\spell_check\core
 */
class SpellTextChecker implements ISpellTextChecker
{
    /** @var DictionaryChecker[] $aDictionaries */
    protected $aDictionaries = [];

    /**
     * SpellTextChecker constructor.
     * @param IDictionaryChecker ...$aDictionaries
     */
    public function __construct(IDictionaryChecker ...$aDictionaries)
    {
        $this->aDictionaries = $aDictionaries;
    }

    /**
     * Fixes mistakes in a string
     *
     * @param string $sText - text
     * @return string
     */
    public function fixMistakes($sText)
    {
        $that = $this;

        return preg_replace_callback(
            '/[а-яА-ЯA-Za-z]*/iu',
            function($sWord) use($that) {
                return $that->matchReplacement($sWord[0]);
            },
            $sText
        );
    }

    /**
     * Decides you need to replace or not
     *
     * @param string $sWord - word
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function matchReplacement($sWord)
    {
        $iMinLevenshtein = 100;

        if ($sWord) {
            for ($i = 0; $i < count($this->aDictionaries); $i++) {
                $sRightWord = $this->aDictionaries[$i]->getRightWord($sWord);

                if ($sRightWord) {
                    $iLevenshtein = levenshtein($sWord, $sRightWord);

                    if ($iMinLevenshtein >= $iLevenshtein) {
                        $iMinLevenshtein = $iLevenshtein;
                        $sWord = $sRightWord;
                    }
                }
            }
        }

        return $sWord;
    }
}