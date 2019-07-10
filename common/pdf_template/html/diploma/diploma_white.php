<?php
/**
 * @var $race \cabinet\entities\cabinet\Race
 * @var $user \cabinet\entities\user\User
 * @var $result integer
 * @var $intervalDate integer
 * @var $position integer
 */
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Диплом участника</title>
        <style>
            .wrap {
                background-image: url(<?= \Yii::getAlias('@common') . '/pdf_template/html/diploma/images/bg.jpg' ?>);
                height: 1120px;
            }
            .red {color:red;}
            .content {

            }
            .title1 {
                margin-top: 97px;
                margin-left: 85px;
                color: white;
                font-size: 25px;
            }
            .title2 {
                margin-top: 245px;
                margin-left: 200px;
                font-size: 90px;
                color: white;
            }
            .name {
                text-transform: uppercase;
                margin-top: 60px;
                font-size: 38px;
                text-align: center;
            }
            .result {
                margin-top: 40px;
                font-size: 20px;
                text-align: right;
                margin-right: 190px;
            }
            .distance, .time, .date {
                margin-top: 10px;
                font-size: 20px;
                text-align: right;
                margin-right: 190px;
            }
            .distance {
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>      
        <div class="wrap">
            <div class="content">
                <div class="title1"><span style="font-size: 74px;">БЕГ</span><br>В РЕЖИМЕ<br><span style="font-size:31px;">ОНЛАЙН</span></div>
                <div class="title2">ДИПЛОМ<br><span style="font-size:40px;margin-left:160px;">УЧАСТНИКА</span></div>
                    <div class="name"><?= $user->profile->first_name . "\n" . $user->profile->last_name ?></div>
                    <div class="result">ЗАНЯВШЕГО <span class="red"><?= $position ?> МЕСТО</span></div>
                    <?php if($result): ?>
                    <div class="distance">НА ДИСТАНЦИИ <span class="red"><?= $result ?></span></div>
                    <?php endif; ?>
                <div class="time">ВРЕМЯ: <span class="red">ЗА <?= $intervalDate ?> ДНЕЙ</span></div>
                <div class="date">ДАТА: <span class="red"><?= date('d.m.Y', strtotime($race->date_end)) ?></span></div>
            </div>
        </div>
    </body>
</html>


                
                