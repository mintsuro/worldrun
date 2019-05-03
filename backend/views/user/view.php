<?php

use cabinet\helpers\UserHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;
use yii\widgets\DetailView;
use cabinet\helpers\ProfileHelper;
use cabinet\entities\user\Profile;

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\user\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => UserHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Роль',
                        'value' => implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'description')),
                        'format' => 'raw',
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <h3>Данные пользователя</h3>
            <?= DetailView::widget([
                'model' => $model->profile,
                'attributes' => [
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'sex',
                        'value' => function(Profile $model){
                            return ProfileHelper::sexLabel($model->sex);
                        },
                        'format' => 'raw',
                    ],
                    'age',
                    'city',
                    'phone',
                    'postal_code',
                    'address_delivery',
                    'size_costume',
                ],
            ]) ?>
        </div>
    </div>
</div>
