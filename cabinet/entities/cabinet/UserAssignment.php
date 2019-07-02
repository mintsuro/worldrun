<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use cabinet\entities\user\User;

/**
 * @property integer $race_id;
 * @property integer $user_id;
 * @property integer $start_number
 * @property bool $notify_send
 *
 * @property User $user
 */
class UserAssignment extends ActiveRecord
{
    public static function create($userId, $raceId, $start_number): self
    {
        $assignment = new static();
        $assignment->user_id = $userId;
        $assignment->race_id = $raceId;
        $assignment->start_number = $start_number;
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

    ##############################

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}