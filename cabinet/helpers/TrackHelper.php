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
            Track::STATUS_MODERATION => 'На модерации',
            Track::STATUS_WAIT => 'В ожидании',
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
                $class = 'label label-danger';
                break;
            case Track::STATUS_WAIT:
                $class = 'label label-danger';
                break;
            case Track::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
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
}