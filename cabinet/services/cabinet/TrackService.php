<?php

namespace cabinet\services\cabinet;

use cabinet\forms\cabinet\DownloadScreenForm;
use cabinet\repositories\cabinet\TrackRepository;
use cabinet\readModels\cabinet\TrackReadRepository;
use cabinet\entities\cabinet\Track;
use cabinet\entities\cabinet\Race;
use Yii;

class TrackService
{
    private $tracks;
    private $readRepository;

    public function __construct(TrackRepository $tracks, TrackReadRepository $readRepository)
    {
        $this->tracks = $tracks;
        $this->readRepository = $readRepository;
    }

    /**
     * @param array $athleteData
     * @param integer $raceId
     * @param integer $trackId
     */
    public function add($athleteData, $raceId, $trackId)
    {
        try {
            foreach (array_reverse($athleteData) as $activity) {
                if ($activity['id'] == $trackId) {
                    $track = Track::create($activity['distance'], $activity['average_speed'],
                        $activity['elapsed_time'], $activity['start_date_local'],
                        $activity['id'], $raceId
                    );
                    $this->tracks->save($track);
                    break;
                }
            }
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function download($athleteData): array
    {
        $tracks = [];
        try {
            foreach (array_reverse($athleteData) as $activity) {
                if ($activity['manual'] === false && $activity['type'] == 'Run') {
                    if (!$this->readRepository->getByTrackId($activity['id'])) {
                        $tracks[] = [
                            'distance' => $activity['distance'],
                            'average_speed' => $activity['average_speed'],
                            'elapsed_time' => $activity['elapsed_time'],
                            'start_date_local' => $activity['start_date_local'],
                            'id' => $activity['id']
                        ];
                    }
                }
            }

            return $tracks;
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    /**
     * @param string $str_time
     * @return integer
     */
    private function convertTimeToSeconds($str_time): int
    {
        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
        $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

        return $time_seconds;
    }

    public function addFromScreen($raceId, DownloadScreenForm $form): void
    {
        $time = $this->convertTimeToSeconds($form->elapsed_time);
        $track = Track::createFromScreen($form->file_screen, $form->distance, $form->date_start, $time, $raceId);
        $this->tracks->save($track);
        $form->upload($track);
    }

    /**
     * Возвращение результата позициии пользователя в забеге
     * @param Race $race
     * @return array
     */
    public function sumResult(Race $race): array
    {
        if($race->type == Race::TYPE_MULTIPLE){
            // Многозагрузочный забег
            $sql = "SELECT SUM(distance) AS sum_distance, user_id FROM cabinet_tracks WHERE race_id = $race->id 
              AND status = '". Track::STATUS_ACTIVE ."' GROUP BY user_id ORDER BY sum_distance DESC";
        }else{
            // Однозагрузочный забег
            $sql = "SELECT SUM(elapsed_time) AS time, SUM(distance) AS sum_distance, user_id FROM cabinet_tracks WHERE race_id = $race->id
                AND status = '". Track::STATUS_ACTIVE ."' GROUP BY user_id ORDER BY time ASC";
        }

        $query = \Yii::$app->db->createCommand($sql)->queryAll();

        return $query;
    }
}