<?php
namespace cabinet\forms\shop\order;

use cabinet\forms\CompositeForm;

/**
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderForm extends CompositeForm
{
    public $note;

    public function __construct(array $config = [])
    {
        $this->delivery = new DeliveryForm();
        $this->customer = new CustomerForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['note'], 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }
}