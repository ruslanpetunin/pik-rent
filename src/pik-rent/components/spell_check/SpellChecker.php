<?php

namespace app\components\spell_check;

use app\components\spell_check\core\DictionaryChecker;
use app\components\spell_check\core\SpellTextChecker;
use app\components\spell_check\interfaces\ISpellCheckerComponent;
use app\components\spell_check\models\HashSignatureModel;
use app\components\spell_check\models\TwoGramModel;
use app\components\spell_check\strategies\HashSignatureStrategy;
use app\components\spell_check\strategies\TwoGramStrategy;
use app\models\active\Dictionary;
use yii\base\Component;
use yii\web\ServerErrorHttpException;

/**
 * Class SpellChecker
 *
 * It's a kinda controller and contain protected mini factory
 *
 * @package app\components\spell_check
 */
class SpellChecker extends Component implements ISpellCheckerComponent
{
    /** @var DictionaryChecker $oDictionaryHSS - saving and searching words */
    protected $oDictionaryHSS;

    /** @var DictionaryChecker $oDictionaryTGS - saving and searching words */
    protected $oDictionaryTGS;

    /** @var SpellTextChecker $oSpellTextChecker - fixing text errors */
    protected $oSpellTextChecker;

    /**
     * Fixes errors of string
     *
     * @param string $sString - text with errors
     * @return string
     * @throws ServerErrorHttpException
     */
    public function fixErrorsInString($sString)
    {
        if (!is_string($sString) || !$sString) {
            throw new ServerErrorHttpException('Spell checker component has got no string');
        }
        
        return $this->getSpellTextChecker()->fixMistakes($sString);
    }

    /**
     * Saves a word to dictionaries
     *
     * @param string $sWord - one word to remember
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function rememberWord($sWord)
    {
        if (!is_string($sWord) || !$sWord) {
            throw new ServerErrorHttpException('Spell checker component has got no string');
        }

        return $this->getDictionaryHSS()->saveRightWord($sWord)
            && $this->getDictionaryTGS()->saveRightWord($sWord);
    }

    /**
     * Make a dictionary with HashSignatureStrategy
     *
     * @return DictionaryChecker
     */
    protected function getDictionaryHSS()
    {
        return $this->oDictionaryHSS ?: ($this->oDictionaryHSS = new DictionaryChecker(
            new HashSignatureStrategy(new HashSignatureModel(), new Dictionary())
        ));
    }

    /**
     * Make a dictionary with TwoGramStrategy
     *
     * @return DictionaryChecker
     */
    protected function getDictionaryTGS()
    {
        return $this->oDictionaryTGS ?: ($this->oDictionaryTGS = new DictionaryChecker(
            new TwoGramStrategy(new TwoGramModel(), new Dictionary())
        ));
    }

    /**
     * Make a SpellTextChecker with both strategy
     *
     * @return SpellTextChecker
     */
    protected function getSpellTextChecker()
    {
        return $this->oSpellTextChecker ?: ($this->oSpellTextChecker = new SpellTextChecker(
            $this->getDictionaryHSS(), $this->getDictionaryTGS()
        ));
    }
}