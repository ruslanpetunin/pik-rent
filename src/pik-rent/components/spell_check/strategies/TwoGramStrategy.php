<?php

namespace app\components\spell_check\strategies;

use app\components\spell_check\interfaces\IDictionaryRecord;
use app\components\spell_check\interfaces\IStrategyHelpRecord;
use app\components\spell_check\interfaces\SpellCheckerStrategy;
use app\components\spell_check\models\TwoGramModel;
use app\models\active\TwoGramWord;
use yii\db\ActiveRecord;

/**
 * Class TwoGramStrategy
 *
 * Helps to find and save a word in n-gram algorithm
 *
 * @package app\components\spell_check\strategies
 */
class TwoGramStrategy extends SpellCheckerStrategy
{

    /** @var TwoGramModel $oGramRecord - model for saving grams of word */
    protected $oGramRecord;

    /**
     * NGramStrategy constructor.
     *
     * @param IStrategyHelpRecord $oGramRecord
     * @param IDictionaryRecord $oWordRecord
     */
    public function __construct(IStrategyHelpRecord $oGramRecord, IDictionaryRecord $oWordRecord)
    {
        $this->oGramRecord = $oGramRecord;

        parent::__construct($oWordRecord);
    }

    /**
     * Finds words in a special dictionary
     *
     * @param string $sWord - checking word
     * @return string[]
     * @throws \yii\base\InvalidConfigException
     */
    public function find($sWord)
    {
        $aGrams = $this->getGrams($sWord);

        return $this->oGramRecord->getWordsBy(...$aGrams);
    }

    /**
     * Saves word in a special dictionary
     *
     * @param string $sWord - checking word
     * @return bool
     */
    public function save($sWord)
    {
        $iIdFromDictionary = $this->saveToMainDictionary($sWord);
        $aGrams = $this->getGrams($sWord);

        for ($i = 0; $i < count($aGrams); $i++) {
            if (!$this->oGramRecord->saveNewWord($iIdFromDictionary, $aGrams[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets grams from string
     *
     * @param string $sWord - word
     * @return array
     */
    public function getGrams($sWord)
    {
        $sWord = mb_convert_case($sWord, MB_CASE_LOWER);
        $aGrams = [];

        for ($i = 1; $i < iconv_strlen($sWord); $i++) {
            $aGrams[] = mb_substr($sWord, $i - 1, 2);
        }

        return $aGrams;
    }
}