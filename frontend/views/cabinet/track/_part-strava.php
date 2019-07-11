<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 * @var $urlOAuth \Strava\API\OAuth
 */
?>

<div style="margin-bottom: 20px; padding-top: 30px" class="">
    <?php if(!$user->strava) : ?>
        <?= Html::a(Html::encode('Подключить Strava'),
            Url::to($urlOAuth),
            ['class' => 'btn btn-success']
        ); ?>
    <?php else : ?>
        <?= Html::a(Html::encode('Загрузить треки из Strava'),
            Url::to(['/cabinet/track/download', 'raceId' => \Yii::$app->request->getQueryParam('raceId')]),
            ['class' => 'btn btn-success', 'id' => 'btn-download']
        ); ?>
        <?= Html::a(Html::encode('Сменить аккаунт Strava'),
            Url::to($urlOAuth),
            ['class' => 'btn btn-success']
        ); ?>
    <?php endif; ?>
    <div id="download-block"><div class="loader"></div></div>
</div>

<?php $this->registerJs("
    // Ajax загрузка треков из Strava
    $('#btn-download').click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        
        $.ajax({
            url: url,
            type: 'POST',
            success: function(data){
                $('#download-block').html(data);
            },
            error: function(e){
                console.log(e);
            },
            beforeSend: function(){
                $('#download-block').find('.loader').show();
            },
            complete: function(){
                $('#download-block').find('.loader').hide();
            }
        });
    });
", $this::POS_END) ?>