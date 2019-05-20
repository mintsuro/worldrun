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

    public function __construct(Order $order, array $config = [])
    {
        $this->delivery = new DeliveryForm($order);
        $this->customer = new CustomerForm($order);
        parent::__construct($config);
    }

    /*public function rules(): array
    {
        return [
            [['note'], 'string'],
        ];
    } */

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }
}