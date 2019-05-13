<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveRecord;

/**
 * @property integer $race_id;
 * @property integer $user_id;
 */
class UserAssignment extends ActiveRecord
{
    public static function create($userId, $raceId): self
    {
        $assignment = new static();
        $assignment->user_id = $userId;
        $assignment->race_id = $raceId;
        return $assignment;
    }

    public function isForUser($id): bool
    {
        return $this->user_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%cabinet_user_participation}}';
    }
}