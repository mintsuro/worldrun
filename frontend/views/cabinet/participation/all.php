<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;

$this->title = 'Забеги';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider
]) ?>