<?php

namespace cabinet\services\cabinet;

use cabinet\forms\cabinet\DownloadScreenForm;
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
                            $activity['elapsed_time'], $activity['start_date_local'],
                            $activity['id'], $raceId
                        );
                        $this->tracks->save($track);
                        break;
                    }
                }
            }
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
        }
    }

    public function addFromScreen($raceId, DownloadScreenForm $form): void
    {
        $track = Track::createFromScreen($form->file, $form->distance, $form->date_start, $form->elapsed_time, $raceId);
        $this->tracks->save($track);
    }
}