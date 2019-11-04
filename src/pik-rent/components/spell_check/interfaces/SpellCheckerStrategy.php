<?php

namespace app\components\spell_check\interfaces;

use app\models\active\Dictionary;

/**
 * Interface SpellCheckerStrategy
 *
 * Strategy of finding right word which was saved in a special way
 *
 * @package app\components\spell_check_interfaces
 */
abstract class SpellCheckerStrategy
{

    /** @var Dictionary $oWordRecord - model for word saving */
    protected $oWordRecord;

    /**
     * SpellCheckerStrategy constructor.
     *
     * @param IDictionaryRecord $oWordRecord
     */
    public function __construct(IDictionaryRecord $oWordRecord)
    {
        $this->oWordRecord = $oWordRecord;
    }

    /**
     * Finds words in a special dictionary
     *
     * @param string $sWord - checking word
     * @return string[]
     */
    public abstract function find($sWord);

    /**
     * Saves word in a special dictionary
     *
     * @param string $sWord - checking word
     * @return bool
     */
    public abstract function save($sWord);

    /**
     * Saves to the main dictionary
     *
     * @param string $sWord - saving word
     * @return int|null
     */
    protected function saveToMainDictionary($sWord)
    {
        return $this->oWordRecord->saveNewWord($sWord);
    }
}