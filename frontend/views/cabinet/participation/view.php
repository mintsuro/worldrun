<?php
/**
 * @var $race \cabinet\entities\cabinet\Race
 * @var $users \cabinet\entities\user\User[]
 */

use yii\helpers\Html;
use cabinet\helpers\RaceHelper;

$this->title = $race->name;
?>

<div class="panel panel-default">
    <div class="panel-heading"><?= $race->name ?></div>
    <div class="panel-body race-item detail">
        <div class="thumbnail">
            <?php if ($race->photo):
                echo Html::img($race->getThumbFileUrl('photo', 'thumb'), ['class' => 'img-responsive']); ?>
            <?php endif; ?>
        </div>
        <div class="info-race">
            <div class="info-text">
                <h4>Дата проведения:</h4>
                <span><strong><?= date('d.m.Y', strtotime($race->date_start)) ?></strong></span> -
                <span><strong><?= date('d.m.Y', strtotime($race->date_end)) ?></strong></span>
            </div>
            <div class="info-text">
                <h4>Период регистрации:</h4>
                <span><strong><?= date('d.m.Y', strtotime($race->date_reg_from)) ?></strong></span> -
                <span><strong><?= date('d.m.Y', strtotime($race->date_reg_to)) ?></strong></span>
            </div>
            <div class="info-text">
                <span><?= RaceHelper::statusSpecLabel($race->status,  $race->date_reg_from, $race->date_reg_to) ?></span>
            </div>
            <div class="info-text">
                <p><?= $race->description ?></p>
            </div>
        </div>
    </div>
</div>

<h4>Участники</h4>
<?= $this->render('_users', [
    'model' => $race,
    'users' => $users,
]) ?>