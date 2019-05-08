<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveRecord;

/**
 * @property integer $race_id;
 * @property integer $user_id;
 */
class UserAssignments extends ActiveRecord
{
    public static function create($userId): self
    {
        $assignment = new static();
        $assignment->user_id = $userId;
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