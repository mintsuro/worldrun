<?php

namespace cabinet\services\cabinet;

use cabinet\repositories\cabinet\TrackRepository;
use cabinet\readModels\cabinet\TrackReadRepository;
use cabinet\entities\cabinet\Track;
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
     */
    public function add($athleteData, $raceId)
    {
        try {
            foreach (array_reverse($athleteData) as $activity) {
                if ($activity['manual'] === false && $activity['type'] == 'Run') {
                    if (!$this->readRepository->getByTrackId($activity['id'])) {
                        $track = Track::create($activity['distance'], $activity['average_speed'],
                            $activity['elapsed_time'], Track::STRAVA_DOWNLOAD,
                            $activity['start_date_local'], $activity['id'], $raceId
                        );
                        $this->tracks->save($track);
                        break;
                    }/* else{
                        // предусмотреть логику если нет новых загруженных треков в Strava
                        Yii::$app->session->setFlash('error','Новых загруженных треков не обнаружено');
                        return false;
                    } */
                }else{
                    Yii::$app->session->setFlash('error','Новых загруженных треков не обнаружено');
                    return false;
                }
            }
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
        }
    }
}