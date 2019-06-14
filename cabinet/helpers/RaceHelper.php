<?php

namespace cabinet\helpers;

use cabinet\entities\cabinet\Race;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\base\InvalidArgumentException;

class RaceHelper
{
    public static function statusList(): array
    {
        return [
            Race::STATUS_REGISTRATION => 'Регистрация',
            Race::STATUS_WAIT => 'Идет забег',
            Race::STATUS_COMPLETE => 'Завершен'
        ];
    }

    public static function typeList(): array
    {
        return [
            Race::TYPE_MULTIPLE => 'Многозагрузочный',
            Race::TYPE_SIMPLE => 'Однозагрузочный',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Race::STATUS_REGISTRATION:
                $class = 'label label-default';
                break;
            case Race::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case Race::STATUS_COMPLETE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function statusSpecLabel($status, $dateRegTo): string
    {
        switch ($status) {
            case Race::STATUS_REGISTRATION:
                $class = 'label label-default';
                break;
            case Race::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case Race::STATUS_COMPLETE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        $str = (strtotime($dateRegTo) > time()) ? 'Регистрация' : '';

        return "<span style='display: block;'>$str</span>" .
        Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function getTemplate($directoryName): array
    {
        try{
            $path = \Yii::getAlias('@common') . "/pdf_template/html/{$directoryName}";
            $files = FileHelper::findFiles($path, ['only' => ['*.php'], 'recursive' => false]);

            if($files){
                $files = array_map(function($file){
                    return basename($file);
                }, $files);

                $files = array_combine($files, $files);

                return $files;
            }
        }catch(InvalidArgumentException $e){
            Yii::$app->errorHandler->logException($e);
        }

        return [];
    }
}