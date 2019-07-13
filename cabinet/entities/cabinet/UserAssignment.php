<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use cabinet\entities\user\User;
use cabinet\entities\cabinet\Race;

/**
 * @property integer $race_id;
 * @property integer $user_id;
 * @property integer $start_number
 * @property bool $notify_start
 * @property bool $notify_end
 * @property bool $notify_finish
 *
 * @property User $user
 * @property Race $race
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

    public function getRace(): ActiveQuery
    {
        return $this->hasOne(Race::class, ['id' => 'race_id']);
    }
}