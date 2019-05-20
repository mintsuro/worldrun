<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use cabinet\entities\cabinet\Track;

$this->title= 'Мои треки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-update">
    <div class="row">
        <div class="col-sm-12">
            <h3 style="margin-top: 0"><?= Html::encode($this->title) ?></h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'layout' => "{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-participant'],
                'columns' => [
                    'created_at',
                    'time_race',
                    'distance',
                    'pace',
                ],
            ]) ?>
            <div style="margin-bottom: 20px" class="">
                <?= Html::a(Html::encode('Загрузить трек'),
                    Url::to(['#']),
                    ['class' => 'btn btn-success']
                ); ?>
            </div>
        </div>
    </div>
</div>
