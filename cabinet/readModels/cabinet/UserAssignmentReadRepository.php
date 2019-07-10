<?php

namespace cabinet\readModels\cabinet;

use cabinet\entities\cabinet\UserAssignment;
use cabinet\repositories\NotFoundException;

class UserAssignmentReadRepository
{
    public function getUserByRace($userId, $raceId): UserAssignment
    {
        if(!$model = UserAssignment::findOne(['user_id' => $userId, 'race_id' => $raceId])){
            throw new NotFoundException('Зарегистрированный пользователь не найден');
        }

        return $model;
    }
}