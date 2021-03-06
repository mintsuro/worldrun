<?php
/**
 * @var $race \cabinet\entities\cabinet\Race
 * @var $user \cabinet\entities\user\User
 * @var $result integer
 * @var $intervalDate integer
 * @var $position integer
 */

use cabinet\helpers\TrackHelper;
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Диплом участника</title>
        <style>
            body {
                color: #fff;
            }
            .wrap {
                background-color: #000;
                padding: 5px;
            }
            .logo {
                width: 200px;
                border-left: 4px solid red;
                margin-left: 75px;
                padding-top: 30px;
            }
            .content {
                width: 500px;
                margin-top: 60px;
                border-right: 4px solid red;
            }
            .str1, .str2 {
                color: white;
                font-weight: bold;
                text-align: right;
                margin-right: 10px;
            }
            .str1 {
                font-size: 80px
            }
            .str2 {
                font-size: 50px;
            }
            .brand {
                margin-left: 450px;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="logo">
                <img src="<?= \Yii::getAlias('@common') . '/pdf_template/html/diploma/images/logo.jpg' ?>" alt="logo" style='width: 200px;'>
            </div>
            <div class="content">
                <div class="str1">ДИПЛОМ</div>
                <div class="str2">учаcтника</div>
                    <div style='color:red;font-size:50px;font-weight:bold;text-align:right;margin:30px 30px;'><?= $user->profile->first_name . "\n" . $user->profile->last_name ?></div>
                    <div style='color:red;font-size:30px;font-weight:bold;text-align:right;margin-right:30px;'>ЗАНЯВШЕГО <?= $position ?> МЕСТО</div>
                    <?php if($race->type == $race::TYPE_MULTIPLE): ?>
                    <div style='color:red;font-size:20px;font-weight:bold;text-align:right;margin-right:30px;margin-top:20px;'><span style="color:#fff;">И ПРЕОДОЛЕВШЕГО:</span> <?= $result['sum_distance'] ?><span style="color:#fff;"> ЗА <?= $intervalDate ?> ДНЕЙ</span></div>
                    <?php endif; ?>
                    <?php if($race->type == $race::TYPE_SIMPLE): ?>
                        <div style='color:red;font-size:20px;font-weight:bold;text-align:right;margin-right:30px;margin-top:20px;'><span style="color:#fff;">И ПРОБЕЖАВШЕГО ЗА:</span> <?= TrackHelper::convertTime($result['time']) ?> ВРЕМЯ</div>
                    <?php endif; ?>
                <div style="color:white;font-size:20px;font-weight:bold;text-align:right;margin-right:30px;margin-top:10px;">ДАТА СТАРТА: <span style="color:red;"><?= date('d.m.Y', strtotime($race->date_start)) ?></span></div>
                <div style="color:white;font-size:20px;font-weight:bold;text-align:right;margin-right:30px;margin-top:10px;">ДАТА ФИНИША: <span style="color:red;"><?= date('d.m.Y', strtotime($race->date_end)) ?></span></div>
                <img src="<?= \Yii::getAlias('@common') . '/pdf_template/html/diploma/images/text.jpg' ?>" alt="text" width="600" style="margin: 40px 30px 40px;">
                <div class="brand">
                    <img src="<?= \Yii::getAlias('@common') . '/pdf_template/html/diploma/images/brand.jpg' ?>" alt="brand" width="250" style="margin-bottom:50px;">
                </div>
            </div>
        </div>
    </body>
</html>