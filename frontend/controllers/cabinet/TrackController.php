<?php

namespace frontend\controllers\cabinet;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use cabinet\entities\cabinet\Track;

class TrackController extends Controller
{
    public $layout = 'cabinet';

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Track::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}