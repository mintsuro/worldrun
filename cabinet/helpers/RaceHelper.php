<?php
namespace cabinet\helpers;

use cabinet\entities\cabinet\Race;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;

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

    public static function statusSpecLabel($status, $dateRegFrom, $dateRegTo): string
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

        if(strtotime($dateRegTo) > time()){
            $tag = Html::tag('span', 'Идет регистрация', [
                'class' => 'label alt label-default'
            ]);
        }elseif(strtotime($dateRegFrom) > time()){
            $tag = Html::tag('span', 'Скоро', [
                'class' => 'label alt label-default'
            ]);
        }else{
            $tag =  '';
        }

        return $tag .
        Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function buttonLabel(Race $model): string
    {
        $flag = true;

        foreach($model->users as $user):
            if($user->id == Yii::$app->user->identity->getId()) {
                $flag = false; // если такой пользователь зарегистрирован в забеге, то записываем false
                break;
            }
        endforeach;

        if($flag){
            if ((strtotime($model->date_reg_to) > time()) && (strtotime($model->date_reg_from) < time())):
                $tag =  Html::a('Участвовать', Url::to(['/shop/checkout', 'raceId' => $model->id]),
                    ['class' => 'btn btn-success']);
                return $tag . Html::a('Подробнее', Url::to(['/cabinet/participation/view', 'id' => $model->id]),
                    ['class' => 'btn btn-success']);
            else:
                return Html::a('Подробнее', Url::to(['/cabinet/participation/view', 'id' => $model->id]),
                    ['class' => 'btn btn-success']);
            endif;
        }else{
            return Html::a('Подробнее', Url::to(['/cabinet/participation/view', 'id' => $model->id]),
                ['class' => 'btn btn-success']);
        }
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