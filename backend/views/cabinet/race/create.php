<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\cabinet\RaceForm */

$this->title = 'Созать забег';
$this->params['breadcrumbs'][] = ['label' => 'Забеги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
