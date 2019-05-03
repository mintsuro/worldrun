<?php

namespace cabinet\repositories\cabinet;

use cabinet\entities\user\Profile;
use cabinet\repositories\NotFoundException;

class ProfileRepository
{
    public function get($id): Profile
    {
        if (!$profile = Profile::findOne($id)) {
            throw new NotFoundException('Профиль пользователя не найден.');
        }
        return $profile;
    }
    
    public function getByUser($id): Profile
    {
        if(!$profile = Profile::findOne(['user_id' => $id])){
            throw new NotFoundException('Профиль пользователя не найден.');
        }
        return $profile;
    }

    public function save(Profile $profile): void
    {
        if (!$profile->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Profile $profile): void
    {
        if (!$profile->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }
}