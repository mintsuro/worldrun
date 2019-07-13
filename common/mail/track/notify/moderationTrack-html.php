<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $track \cabinet\entities\cabinet\Track
 */

use yii\helpers\Html;
use cabinet\entities\cabinet\Track;

?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Ваш скриншот обработан и <?php if($track->status == Track::STATUS_CANCEL){
        $text = "Отклонен. Причина: <br/>";
        if($track->cancel_reason == Track::CANCEL_DATE){
            echo  $text . 'Неверная дата.';
        }else if($track->cancel_reason == Track::CANCEL_SIMILARITY){
            echo $text . 'Такой результат уже был загружен.';
        }else if($track->cancel_reason == Track::CANCEL_DATA){
            echo $text . 'На скриншоте нет данных о пробежке.';
        }else if($track->cancel_reason == Track::CANCEL_OTHER){
           echo $text . 'Другая причина' . '<br/>' . $track->cancel_text;
        }else{
            echo 'Отклонен';
        }
    }else{
        echo 'Принят';
    } ?>.</p>
</div>
