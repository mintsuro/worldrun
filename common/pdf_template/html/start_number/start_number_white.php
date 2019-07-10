<?php
/**
 * @var $startNumber string
 * @var $race \cabinet\entities\cabinet\Race
 */
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Anime</title>
        <style>
            body {
                color: #fff;
            }
            .wrap {
                background-image: url(<?= \Yii::getAlias('@common') . '/pdf_template/html/start_number/images/bg.jpg' ?>);
                height: 559px;
            }
            .content {
                width: 400px;
                text-align: center;
            }
            .number {
                font-size: 150px;
                color: white;
                margin-top: 170px;
            }
            .name {
                font-size: 50px;
                margin-top: 12px;
                color: black;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="content">
                <?php if($startNumber): ?>
                    <div class="number"><?= $startNumber ?></div>
                <?php endif; ?>
                
                <?php if($race->user->profile->first_name): ?>
                    <div class="name"><?= $race->user->profile->first_name?></div>
                <?php endif; ?>
            </div>
        </div>    
    </body>
</html>