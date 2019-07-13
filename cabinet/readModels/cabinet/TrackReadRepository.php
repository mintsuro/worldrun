<?php

namespace cabinet\readModels\cabinet;

use cabinet\entities\cabinet\Track;
use cabinet\repositories\NotFoundException;

class TrackReadRepository
{
    public function getByUser($id): Track
    {
        if(!$track = Track::findOne(['user_id' => $id])){
            throw new NotFoundException('Трек пользователя не найден.');
        }
        return $track;
    }

    public function getByTrackId($id): ?Track
    {
        $track = Track::findOne(['id_strava_track' => $id]);
        return $track;
    }
}