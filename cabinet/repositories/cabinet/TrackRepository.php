<?php

namespace cabinet\repositories\cabinet;

use cabinet\repositories\NotFoundException;
use cabinet\entities\cabinet\Track;

class TrackRepository
{
    public function get($id): Track
    {
        if (!$track = Track::findOne($id)) {
            throw new NotFoundException('Трек не найден.');
        }
        return $track;
    }

    public function save(Track $track): void
    {
        if (!$track->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Track $track): void
    {
        if (!$track->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }
}