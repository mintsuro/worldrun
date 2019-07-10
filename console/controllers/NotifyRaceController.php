<?php

namespace console\controllers;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\UserAssignment;
use cabinet\readModels\cabinet\RaceReadRepository;
use cabinet\services\manage\cabinet\RaceManageService;
use yii\console\Controller;

class NotifyRaceController extends Controller
{
    private $repository;
    private $service;

    public function __construct(string $id, $module,
        RaceReadRepository $repository,
        RaceManageService $service,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->repository = $repository;
        $this->service = $service;
    }

    public function actionStart()
    {
        $races = $this->repository->getByStartDate()->all();

        if($races){
            try{
                $result = $this->service->notifyStart($races);
                $this->stdout($result . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
                $this->stdout($e->getMessage() . PHP_EOL);
            }
        }

        /** @var $result string */
        $this->stdout($result . PHP_EOL);
        return true;
    }

    public function actionEnd()
    {
        $races =  $this->repository->find()
            ->where(['<=', 'date_start', date('Y-m-d H:i:s')])
            ->andWhere(['>=', 'date_end', date('Y-m-d H:i:s'), time() - 3600 * 24])
            ->andWhere(['type' => Race::TYPE_MULTIPLE])
            ->orderBy(['r.date_start', SORT_ASC])
            ->all();

        if($races){
            try{
                $result = $this->service->notifyEnd($races);
                $this->stdout($result . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
                $this->stdout($e->getMessage() . PHP_EOL);
            }
        }

        /** @var $result string */
        $this->stdout($result . PHP_EOL);
        return true;
    }

    public function actionFinish()
    {
        $races = $this->repository->getByFinishDate()->all();

        if($races){
            try{
                $result = $this->service->notifyFinish($races);
                $this->stdout($result . PHP_EOL);
                exit;
            }catch(\DomainException $e){
                \Yii::$app->errorHandler->logException($e);
                $this->stdout($e->getMessage() . PHP_EOL);
            }
        }

        /** @var $result string */
        $this->stdout($result . PHP_EOL);
        return true;
    }
}