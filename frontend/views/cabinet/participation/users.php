<?php

/*  @var $this yii\web\View
 *  @var $users \cabinet\entities\user\User[]
 *  @var $race \cabinet\entities\cabinet\Race
 */
use yii\helpers\Html;

$this->title = 'Участники ' . $race->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<h3 style="margin-top: 0"><?= Html::encode($this->title) ?></h3>

<?= $this->render('_users', [
    'model' => $race,
    'users' => $users,
]) ?>