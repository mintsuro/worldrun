<?php
/**
 * @var $tracks \cabinet\entities\cabinet\Track[]
 * @var $this \yii\web\View
 * @var $raceId integer
 * @var $model \cabinet\forms\cabinet\DownloadTrackForm
 */

use cabinet\helpers\TrackHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<div class="strava-tracks">
    <h4>Треки из Strava.</h4>
    <em>Выберите нужный трек и загрузите его.</em>
    <?php ActiveForm::begin(['action' => Url::to(['/cabinet/track/add', 'raceId' => $raceId])]) ?>
    <table class="table table-striped table-bordered table-participant">
        <thead>
            <tr>
                <th>Дата/время старта пробежки</th>
                <th>Дистанция</th>
                <th>Время пробежки</th>
                <th>Темп</th>
                <th>Выбрать</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tracks as $track): ?>
                <tr>
                    <td><?= date('d.m.Y H:i:s', strtotime($track['start_date_local'])); ?></td>
                    <td><?= floor($track['distance']) . ' м.'?></td>
                    <td><?= date('H:i:s', $track['elapsed_time']); ?></td>
                    <td><?php echo TrackHelper::getPace($track['average_speed']) ?></td>
                    <td>
                        <div class="check-track">
                            <?= Html::input('radio', 'strava-track', $track['id'], ['class' => 'inp-track', 'id' => 'inp-track-' . $track['id']]) ?>
                            <label for="inp-track-<?= $track['id'] ?>"></label>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= Html::submitButton('Загрузить трек', ['class' => 'btn btn-success btn-add-track']) ?>
    <?php ActiveForm::end() ?>
</div>