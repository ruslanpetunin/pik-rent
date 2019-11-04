<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Class HashSignatureWordQuery
 * @package app\models\queries
 */
class HashSignatureWordQuery extends ActiveQuery
{
    /**
     * Searching words by hash
     *
     * @param string $sHash - md5 of word
     * @return HashSignatureWordQuery
     */
    public function wordsByHash($sHash)
    {
        return $this
            ->where(['hsw_hash' => $sHash])
            ->with('hswWord');
    }
}