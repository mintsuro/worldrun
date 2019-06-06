<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveRecord;

/**
 * @property integer $race_id;
 * @property integer $user_id;
 * @property integer $value;
 */
class StartNumber extends ActiveRecord
{
    public static function create(int $raceId, int $userId): self
    {
        $item = new static();
        $item->race_id = $raceId;
        $item->user_id = $userId;
        $item->value += 1;

        return $item;
    }

    public function isForUser($id): bool
    {
        return $this->user_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%cabinet_user_startnumber}}';
    }
}