<?php

/* @var $this yii\web\View */
/* @var $track \cabinet\entities\cabinet\Track */
/* @var $model \cabinet\forms\manage\cabinet\TrackForm */

$this->title = 'Редактировать трек автора: ' . $track->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Треки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Трек', 'url' => ['view', 'id' => $track->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="page-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
