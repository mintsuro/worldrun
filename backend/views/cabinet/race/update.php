<?php

/* @var $this yii\web\View */
/* @var $race \cabinet\entities\cabinet\Race */
/* @var $model \cabinet\forms\manage\cabinet\RaceForm */

$this->title = 'Редактировать забег: ' . $race->name;
$this->params['breadcrumbs'][] = ['label' => 'Забеги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $race->name, 'url' => ['view', 'id' => $race->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
