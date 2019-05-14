<?php

namespace cabinet\forms\shop\order;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use cabinet\helpers\PriceHelper;

class DeliveryForm extends Model
{
    public $index;
    public $address;

    private $_weight;

    public function __construct(int $weight, array $config = [])
    {
        $this->_weight = $weight;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['index', 'address'], 'required'],
            [['index'], 'string', 'max' => 255],
            [['address'], 'string']
        ];
    }
}