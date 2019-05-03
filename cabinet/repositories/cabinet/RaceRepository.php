<?php

namespace cabinet\repositories\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\repositories\NotFoundException;

class RaceRepository
{
    public function get($id): Race
    {
        if (!$race = Race::findOne($id)) {
            throw new NotFoundException('Забег не найден.');
        }
        return $race;
    }

    public function getByUser($id): Race
    {
        if(!$race = Race::findOne(['user_id' => $id])){
            throw new NotFoundException('Забег пользователя не найден.');
        }
        return $race;
    }

    public function save(Race $race): void
    {
        if (!$race->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Race $race): void
    {
        if (!$race->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }
}