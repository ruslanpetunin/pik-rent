<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Class TwoGramWordQuery
 * @package app\models\queries
 */
class TwoGramWordQuery extends ActiveQuery
{
    /**
     * Searching words by hash
     *
     * @param string[] $aGrams - grams
     * @return TwoGramWordQuery
     */
    public function wordsByGrams($aGrams)
    {
        return $this
            ->leftJoin('two_grams', 'tg_id = tgw_gram_id')
            ->where(['in', 'tg_gram', $aGrams])
            ->groupBy('tgw_word_id')
            ->with('tgwWord');
    }
}