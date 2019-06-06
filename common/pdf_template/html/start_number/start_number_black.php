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
        <title>Стартовый номер</title>
        <style>
            body {
                font-family: freeserif;
                color: #fff;
            }
            .wrap {
                background-color: #000;
                padding: 5px;
            }
            .logo {
                width: 200px;
                border-right: 4px solid red;
            }
            .content {
                width: 500px;
                margin-left: 250px;
            }
            .content_ {
                border-top: 4px solid red;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="logo">
                <img src="<?= \Yii::getAlias('@common') . '/pdf_template/html/start_number/images/logo.jpg' ?>" alt="logo" style='width: 200px;'>
            </div>
            <div class="content">
                <?php if($startNumber): ?>
                    <div style='color:white;font-size:100px;font-weight:bold;text-align:left;'><?= $startNumber ?></div>
                <?php endif; ?>

                <div class="content_">
                    <?php if($race->user->profile->first_name): ?>
                        <div style='color:red;font-size:80px;font-weight:bold;text-align:center;'><?= $race->user->profile->first_name ?></div>
                    <?php endif; ?>
                    <img src="<?= \Yii::getAlias('@common') . '/pdf_template/html/start_number/images/brand.jpg' ?>" alt="brand" width="250" style="margin-left:250px;margin-top:96px;">
                </div>
            </div>
        </div>
    </body>
</html>