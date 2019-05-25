<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $users \cabinet\entities\user\User[] */

use yii\helpers\Html;

$this->title = 'Участники';
$this->params['breadcrumbs'][] = $this->title;
?>

<h3 style="margin-top: 0"><?= Html::encode($this->title) ?></h3>
<div>
    <div class="table-responsive">
        <table class="table table-bordered table-users-list">
            <thead>
                <tr>
                    <td class="text-left">Стартовый номер</td>
                    <td class="text-left">Имя Фамилия</td>
                    <td class="text-left">Возраст</td>
                    <td class="text-left">Пол</td>
                    <td class="text-left">Город</td>
                    <td class="text-left">Результат</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user) : ?>
                    <tr>
                        <td class="text-left"><?= $user->id ?></td>
                        <td class="text-left">
                            <?= Html::encode($user->profile->first_name . ' ' . $user->profile->last_name) ?>
                        </td>
                        <td class="text-left">
                            <?= Html::encode($user->profile->age) ?>
                        </td>
                        <td class="text-left">
                            <?= \cabinet\helpers\ProfileHelper::sexConvertString($user->profile->sex) ?>
                        </td>
                        <td class="text-left">
                            <?= Html::encode($user->profile->city) ?>
                        </td>
                        <td class="text-left">Пусто</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>