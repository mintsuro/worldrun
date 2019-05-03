<?php

namespace frontend\controllers\cabinet;

use cabinet\services\cabinet\ProfileService;

use cabinet\entities\user\Profile;
use cabinet\forms\user\ProfileEditForm;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    private $service;

    public function __construct($id, $module, ProfileService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['edit'],
                'rules' => [
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionEdit(): string
    {
        $profile = $this->findModel(Yii::$app->user->identity->getId());
        $form = new ProfileEditForm($profile);

        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try{
                $this->service->edit($profile->user_id, $form);
                return $this->redirect(['/cabinet/tracking/index', 'id' => $profile->user_id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $form,
            'profile' => $profile,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne(['user_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Такой профиль пользователя не существует.');
        }
    }
}