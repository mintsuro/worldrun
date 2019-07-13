<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
use cabinet\readModels\cabinet\UserAssignmentReadRepository;
use cabinet\repositories\NotFoundException;
use cabinet\services\cabinet\PdfTemplateService;
use cabinet\repositories\cabinet\RaceRepository;
use cabinet\services\cabinet\TrackService;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class PdfGeneratorController extends Controller
{
    private $service;
    private $trackService;
    private $repository;
    private $assignmentRepository;

    public function __construct($id, $module,
        PdfTemplateService $service,
        TrackService $trackService,
        RaceRepository $repository,
        UserAssignmentReadRepository $assignmentRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->trackService = $trackService;
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
            $query = $this->trackService->sumResult($race);

            // Определяем место пользователя в забеге
            for ($i = 0, $j = 0; $i < count($query); $i++, $j++) {
                if ($query[$j]['user_id'] == Yii::$app->user->getId()) {
                    $position = $j + 1;
                    //$result = ($race->type == Race::TYPE_MULTIPLE) ? $query[$j]['sum_distance'] : $query[$j]['time'];
                    $result = $query[$j];
                    break;
                }
            }

            $intervalDate = $race->getIntervalDate();
            // Определяем находится ли пользователь в топ-3
            $template = ($position <= 3) ? $race->template->diploma_top : $race->template->diploma;

            $content = $this->renderFile(Yii::getAlias('@common') . "/pdf_template/html/diploma/{$template}", [
                'race' => $race,
                'result' => $result,
                'intervalDate' => $intervalDate,
                'position' => $position,
                'user' => $user,
            ]);

            if(Yii::$app->request->isGet) {
                if(!empty($content)){
                    $this->service->generatePDF($content, $orientationPDF, $formatPDF);
                }
            }

        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->getFlash('error', $e->getMessage());
        }
    }
}