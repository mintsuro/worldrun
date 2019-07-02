<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
use cabinet\entities\cabinet\UserAssignment;
use cabinet\entities\user\User;
use cabinet\repositories\NotFoundException;
use cabinet\services\cabinet\PdfTemplateService;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\Controller;

class PdfGeneratorController extends Controller
{
    public $service;

    public function __construct($id, $module, PdfTemplateService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
        $race = Race::findOne($raceId);
        $tracks = $race->getTracks();
        $distance = $tracks
            ->andWhere(['user_id' => \Yii::$app->user->identity->getId()])
            ->andWhere(['status' => Track::STATUS_ACTIVE])
            ->sum('distance');

        $position = $tracks
            ->select(['SUM(distance)', 'user_id'])
            //->where(['status' => Track::STATUS_ACTIVE])
            ->groupBy('user_id')
            ->orderBy(['SUM(distance)' => SORT_ASC])
            ->all();

        $sql = '';

            $position = \Yii::$app->db->createCommand('SELECT SUM(distance) AS fieldname, user_id AS field2 FROM cabinet_tracks GROUP BY field2 ORDER BY fieldname ASC')->query();
        //$position = (new Query())->createCommand('SELECT SUM(distance) AS fieldname, user_id AS field2 FROM cabinet_tracks GROUP BY field2 ORDER BY fieldname ASC')->query();

        $arr = [];
        foreach ($position as $pos) {

            echo $pos['field2'] . ";\n";
        }

        die();

        $intervalDate = $race->getIntervalDate();
        $content = $this->renderFile(Yii::getAlias('@common') . "/pdf_template/html/diploma/{$race->template->diploma}", [
            'race' => $race,
            'distance' => $distance,
            'intervalDate' => $intervalDate,
        ]);

        if(Yii::$app->request->isGet) {
            if(!empty($content)){
                $this->service->generatePDF($content, $orientationPDF, $formatPDF);
            }else{
                throw new NotFoundException('Файл для генерации шаблона не найден.');
            }
        }
    }
}