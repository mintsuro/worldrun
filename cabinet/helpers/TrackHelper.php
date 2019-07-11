<?php

namespace cabinet\helpers;

use cabinet\entities\cabinet\Track;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\base\InvalidArgumentException;

class TrackHelper
{
    public static function statusList(): array
    {
        return [
            Track::STATUS_ACTIVE => 'Активный',
            Track::STATUS_MODERATION => 'На проверке',
            Track::STATUS_WAIT => 'В ожидании',
            Track::STATUS_CANCEL => 'Отклонен',
        ];
    }

    public static function cancelList(): array
    {
        return [
            Track::CANCEL_DATE => 'Неверная дата',
            Track::CANCEL_SIMILARITY => 'Такой результат уже был загружен',
            Track::CANCEL_DATA => 'На скриншоте нет данных о пробежке',
            Track::CANCEL_OTHER => 'Другая причина',
        ];
    }

    public static function downloadMethodList(): array
    {
        return [
            Track::STRAVA_DOWNLOAD => 'Strava',
            Track::SCREEN_DOWNLOAD => 'Скришнот',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Track::STATUS_MODERATION:
                $class = 'label label-default';
                break;
            case Track::STATUS_WAIT:
                $class = 'label label-danger';
                break;
            case Track::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-danger';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function downloadLabel($method): string
    {
        switch ($method) {
            case Track::STRAVA_DOWNLOAD:
                $class = 'label label-success';
                break;
            case Track::SCREEN_DOWNLOAD:
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::downloadMethodList(), $method), [
            'class' => $class,
        ]);
    }

    // Калькулятор темпа бега
    public static function getPace($pace): string
    {
        $resPace = 1000 / $pace;
        $res = $resPace / 60;
        $strDec = substr(strstr($res, '.'), 1, 1); //strlen(substr(strrchr($res, "."), 1));
        $seconds = $strDec / 10 * 60;
        $sec = strlen($seconds) == 1 ? '0' . $seconds : $seconds;
        $minute = floor($res);
        return $minute . ':' . $sec;
    }

    public static function convertTime($seconds): string
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

        return $timeFormat;
    }
}