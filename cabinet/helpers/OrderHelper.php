<?php

namespace cabinet\helpers;

use cabinet\entities\shop\order\Status;
use cabinet\entities\shop\product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use cabinet\entities\cabinet\Race;

class OrderHelper
{
    public static function statusList(): array
    {
        return [
            Status::NEW => 'Новый',
            Status::PAID => 'Оплачен',
            Status::SENT => 'Отправлен',
            Status::COMPLETED => 'Завершен',
            Status::CANCELLED => 'Отменен',
            Status::CANCELLED_BY_CUSTOMER => 'Отменен пользователем',
        ];
    }

    public static function statusSent(): array
    {
        return [
            Status::SENT => 'Отправлен'
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Product::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Product::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function statusColumn(Race $race): string
    {
        $linkPay = Html::a('Оплатить', Url::to(['/cabinet/order/race', 'raceId' => $race->id]), [
            'class' => 'label label-success pay-link']);
        $note = $race->order->current_status == Status::NEW ?
            Html::tag('span', 'Не оплачено', ['class' => 'label alt label-danger']) : null;
        $tag = $race->order->current_status == Status::NEW ? $linkPay : null;
        return Html::a('Подарки', Url::to(['/cabinet/order/race', 'raceId' => $race->id])) . "<br/>" . $tag . $note;
    }
}