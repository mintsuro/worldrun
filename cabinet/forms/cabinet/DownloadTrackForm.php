<?php

namespace cabinet\forms\cabinet;

use yii\base\Model;

class DownloadTrackForm extends Model
{
    public $strava_track;

    public function rules(){
        return [
            [['strava_track'], 'required'],
        ];
    }
}