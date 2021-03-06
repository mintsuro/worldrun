<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $urlOAuth \Strava\API\OAuth
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

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
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                    ],
                    'date_start',
                    'distance',
                    'pace',
                ],
            ]) ?>
        </div>
    </div>
</div>
