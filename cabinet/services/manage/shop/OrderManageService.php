<?php

namespace cabinet\services\manage\shop;

use cabinet\entities\shop\order\CustomerData;
use cabinet\entities\shop\order\DeliveryData;
use cabinet\forms\manage\shop\order\OrderEditForm;
use cabinet\repositories\shop\OrderRepository;

class OrderManageService
{
    private $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);

        $order->edit(
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            )
        );

        $order->setDeliveryInfo(
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }
}