<?php

namespace app\components\spell_check\strategies;

use app\components\spell_check\interfaces\IDictionaryRecord;
use app\components\spell_check\interfaces\IStrategyHelpRecord;
use app\components\spell_check\interfaces\SpellCheckerStrategy;
use app\models\active\HashSignatureWord;
use yii\db\ActiveRecord;

/**
 * Class HashSignatureStrategy
 *
 *  Helps to find and save a word in hash signature algorithm
 *
 * @package app\components\spell_check\strategies
 */
class HashSignatureStrategy extends SpellCheckerStrategy
{
    /** @var array $aRegExp - regular expressions for build a hash signature */
    protected $aRegExp = [
        '/[аб]/u', '/[вгд]/u', '/[еёж]/u', '/[зий]/u', '/[кл]/u',
        '/[мно]/u', '/[пр]/u', '/[сту]/u', '/[фх]/u', '/[цчш]/u',
        '/[щъ]/u', '/[ыьэ]/u', '/[юя]/u'
    ];

    /** @var HashSignatureWord $oGramRecord - model for saving grams of word */
    protected $oHashSignatureRecord;

    /**
     * HashSignatureStrategy constructor.
     *
     * @param IStrategyHelpRecord $oHashSignatureRecord
     * @param IDictionaryRecord $oWordRecord
     */
    public function __construct(IStrategyHelpRecord $oHashSignatureRecord, IDictionaryRecord $oWordRecord)
    {
        $this->oHashSignatureRecord = $oHashSignatureRecord;

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
        $sHash = $this->getHash($sWord);

        return $this->oHashSignatureRecord->getWordsBy($sHash);
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
        $sHashWord = $this->getHash($sWord);

        return (bool)$this->oHashSignatureRecord->saveNewWord($iIdFromDictionary, $sHashWord);
    }

    /**
     * Makes a hash signature
     *
     * @param string $sWord - word
     * @return string
     */
    protected function getHash($sWord)
    {
        $sHash = '';

        for ($i = 0; $i < count($this->aRegExp); $i++) {
            preg_match($this->aRegExp[$i], mb_convert_case($sWord, MB_CASE_LOWER), $aMatches);
            $sHash .= count($aMatches) ? '1' : '0';
        }

        return $sHash;
    }
}