<?php

namespace cabinet\forms\manage\shop\order;

use cabinet\entities\shop\order\Order;
use cabinet\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class DeliveryForm extends Model
{
    public $method;
    public $index;
    public $address;
    public $city;

    private $_order;

    public function __construct(Order $order, array $config = [])
    {
        $this->index = $order->deliveryData->index;
        $this->address = $order->deliveryData->address;
        $this->city = $order->deliveryData->city;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['index', 'address'], 'required'],
            [['index', 'address', 'city'], 'string', 'max' => 255],
        ];
    }
}