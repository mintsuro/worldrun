<?php

namespace console\controllers;

use cabinet\entities\cabinet\Race;
use cabinet\readModels\cabinet\RaceReadRepository;
use yii\console\Controller;

class NotifyRaceController extends Controller
{
    private $repository;

    public function __construct(string $id, $module,
        RaceReadRepository $repository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->repository = $repository;
    }

    public function actionStartRace()
    {
        $races = $this->repository;

        if($races){
            try{
                /** @var Race $race */
                foreach($races as $race):
                    foreach($race->userAssignments as $assignment){
                        if($assignment->notify_send == 0){
                            \Yii::$app->mailer->compose($assignment->user->email);
                            $assignment->notify_send = 1;
                            $assignment->save();
                        }
                    }
                endforeach;
                    
                $this->stdout('Send email for notify pay' . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
            }
        }

        $this->stdout('New registration races not found' . PHP_EOL);
        return true;
    }
}