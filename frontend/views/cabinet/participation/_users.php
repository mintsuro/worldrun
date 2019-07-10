<?php
/* @var $this yii\web\View
*  @var $users \cabinet\entities\user\User[]
*  @var $model \cabinet\entities\cabinet\Race
*/

use yii\helpers\Html;
use cabinet\entities\cabinet\Race;
use cabinet\helpers\UserHelper;
?>

<div class="table-responsive">
    <table class="table table-bordered table-users-list">
        <thead>
            <tr>
                <td class="text-left">Стартовый номер</td>
                <td class="text-left">Имя Фамилия</td>
                <td class="text-left">Возраст</td>
                <td class="text-left">Пол</td>
                <td class="text-left">Город</td>
                <td class="text-left">
                    <?php $model->type == Race::TYPE_MULTIPLE ? print('Расстояние') : print('Время') ?>
                </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user) : ?>
                <?php
                /** @var \cabinet\entities\cabinet\UserAssignment $assignment  */
                foreach($user->getUserAssignments()->all() as $assignment):
                    if($assignment->race_id == $model->id){
                        $startNumber = $assignment->start_number;
                        break;
                    }
                endforeach;

                $startNumber = str_pad($startNumber, 4, '0', STR_PAD_LEFT);
                ?>
                <tr>
                    <td class="text-left"><?= $startNumber ?></td>
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
                    <td class="text-left">
                       <?= UserHelper::resultTrack($user->id, $model); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>