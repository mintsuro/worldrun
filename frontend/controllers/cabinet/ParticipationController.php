<?php

namespace frontend\controllers\cabinet;

use cabinet\entities\cabinet\Race;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;

class ParticipationController extends Controller
{
    public $layout = 'cabinet';

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['participant'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' => Race::find(),
            'sort' => false,
            'pagination' => [
                'pageSize' => 12,
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}