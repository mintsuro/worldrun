<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
use cabinet\readModels\cabinet\UserAssignmentReadRepository;
use cabinet\repositories\NotFoundException;
use cabinet\services\cabinet\PdfTemplateService;
use cabinet\repositories\cabinet\RaceRepository;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class PdfGeneratorController extends Controller
{
    private $service;
    private $repository;
    private $assignmentRepository;

    public function __construct($id, $module,
        PdfTemplateService $service,
        RaceRepository $repository,
        UserAssignmentReadRepository $assignmentRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->repository = $repository;
        $this->assignmentRepository = $assignmentRepository;
    }

    /**
     * @param integer $raceId
     * @return mixed
     */
    public function actionGenerateStartNumber($raceId)
    {
        $orientationPDF = 'L';
        $formatPDF = 'A5';
        $race = Race::findOne($raceId);

        $startNumber = $race->getStartNumberForUser($race);
        $startNumber = str_pad($startNumber, 4, '0', STR_PAD_LEFT);
        $html = $this->renderFile(Yii::getAlias('@common') . "/pdf_template/html/start_number/{$race->template->start_number}", [
            'race' => $race,
            'startNumber' => $startNumber
        ]);

        if(Yii::$app->request->isGet) {
            if(!empty($html)){
                $this->service->generatePDF($html, $orientationPDF, $formatPDF);
            }else{
                throw new NotFoundException('Файл для генерации шаблона не найден.');
            }
        }
    }

    /**
     * @param integer $raceId
     * @return mixed
     */
    public function actionGenerateDiploma($raceId)
    {
        $orientationPDF = 'P';
        $formatPDF = 'A4';
        $race = $this->repository->get($raceId);
        $assignment = $this->assignmentRepository->getUserByRace(Yii::$app->user->getId(), $raceId);
        $user = $assignment->user;

        try {
            if($race->type == Race::TYPE_MULTIPLE){
                $sql = "SELECT SUM(distance) AS sum_distance, user_id FROM cabinet_tracks WHERE race_id = $race->id 
              AND status = '". Track::STATUS_ACTIVE ."' GROUP BY user_id ORDER BY sum_distance DESC";
            }else{
                $sql = "SELECT SUM(elapsed_time) AS time, user_id FROM cabinet_tracks WHERE race_id = $race->id
                AND status = '". Track::STATUS_ACTIVE ."' GROUP BY user_id ORDER BY time ASC";
            }

            $query = \Yii::$app->db->createCommand($sql)->queryAll();

            for ($i = 0, $j = 0; $i < count($query); $i++, $j++) {
                if ($query[$j]['user_id'] == Yii::$app->user->getId()) {
                    $position = $j + 1;
                    $result = ($race->type == Race::TYPE_MULTIPLE) ? $query[$j]['sum_distance'] : $query[$j]['time'];
                    break;
                }
            }

            $intervalDate = $race->getIntervalDate();
            $template = ($position <= 3) ? $race->template->diploma_top : $race->template->diploma;
            $content = $this->renderFile(Yii::getAlias('@common') . "/pdf_template/html/diploma/{$template}", [
                'race' => $race,
                'result' => $result,
                'intervalDate' => $intervalDate,
                'position' => $position,
                'user' => $user,
            ]);
        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->getFlash('error', $e->getMessage());
        }

        if(Yii::$app->request->isGet) {
            if(!empty($content)){
                $this->service->generatePDF($content, $orientationPDF, $formatPDF);
            }else{
                throw new NotFoundException('Файл для генерации шаблона не найден.');
            }
        }
    }
}