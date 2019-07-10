<?php

namespace cabinet\forms\manage\shop\order;

use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\Status;
use yii\base\Model;

class OrderSentForm extends Model
{
    public $track_post;
    public $status;

    public function __construct(Order $order, array $config = [])
    {
        $this->track_post = $order->track_post;
        $this->status = Status::SENT;
        parent::__construct($config);
    }

    public function rules(){
        return [
            [['track_post'], 'required'],
            ['track_post', 'string'],
            ['status', 'default', 'value' => Status::SENT],
            ['status', 'in', 'range' => [Status::SENT]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'status' => 'Статус',
            'track_post' => 'Трек-номер почты',
        ];
    }
}