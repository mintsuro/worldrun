<?php

namespace cabinet\forms\manage\shop\order;

use cabinet\entities\shop\order\Order;
use cabinet\forms\CompositeForm;

/**
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderEditForm extends CompositeForm
{
    public $note;
    public $weight;
    public $track_post;
    public $status;

    public function __construct(Order $order, array $config = [])
    {
        $this->delivery = new DeliveryForm($order);
        $this->customer = new CustomerForm($order);
        $this->weight = $order->weight;
        $this->track_post = $order->track_post;
        $this->status = $order->current_status;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['weight', 'double'],
            [['status'], 'integer'],
            [['track_post'], 'string'],
            [['note'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'weight' => 'Вес (в граммах)',
            'track_post' => 'Трек-номер почты',
            'status' => 'Статус',
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }
}