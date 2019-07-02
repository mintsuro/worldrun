<?php

namespace cabinet\entities\shop\order;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
//use shop\entities\shop\DeliveryMethod;
use cabinet\entities\user\User;
use cabinet\entities\cabinet\Race;

/**
 * @property int $id
 * @property int $created_at
 * @property int $user_id
 * @property int $race_id
 * @property string $payment_method
 * @property string $payment_id
 * @property int $cost
 * @property int $current_status
 * @property string $cancel_reason
 * @property string $track_post
 * @property integer $weight
 * @property string $delivery_address
 * @property string $delivery_index
 * @property string $customer_name
 * @property string $customer_phone
 * @property bool $notify_send
 * @property CustomerData $customerData
 * @property DeliveryData $deliveryData
 *
 * @property OrderItem[] $items
 * @property Status[] $statuses
 * @property User $user
 * @property Race $race
 */
class Order extends ActiveRecord
{
    public $customerData;
    public $deliveryData;
    public $statuses = [];

    public static function create($raceId, $userId, CustomerData $customerData, array $items, $cost): self
    {
        $order = new static();
        $order->race_id = $raceId;
        $order->user_id = $userId;
        $order->customerData = $customerData;
        $order->items = $items;
        $order->cost = $cost;
        $order->created_at = time();
        $order->addStatus(Status::NEW);
        return $order;
    }

    public function edit(CustomerData $customerData, $weight, $track_post, $status): void
    {
        $this->customerData = $customerData;
        $this->weight = $weight;
        $this->track_post = $track_post;
        $this->current_status = $status;
    }

    public function setDeliveryInfo(DeliveryData $deliveryData): void
    {
        $this->deliveryData = $deliveryData;
    }

    public function setPaymentId($paymentId): void
    {
        $this->payment_id = $paymentId;
    }

    public function pay($method): void
    {
        if ($this->isPaid()) {
            throw new \DomainException('Заказ уже оплачен.');
        }
        $this->payment_method = $method;
        $this->addStatus(Status::PAID);
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new \DomainException('Заказ уже отправлен.');
        }
        $this->addStatus(Status::SENT);
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new \DomainException('Заказ уже оптравлен.');
        }
        $this->addStatus(Status::COMPLETED);
    }

    public function cancel($reason): void
    {
        if ($this->isCancelled()) {
            throw new \DomainException('Заказ уже отменен.');
        }
        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    public function getTotalCost(): int
    {
        return $this->cost;
    }

    public function canBePaid(): bool
    {
        return $this->isNew();
    }

    public function isNew(): bool
    {
        return $this->current_status == Status::NEW;
    }

    public function isPaid(): bool
    {
        return $this->current_status == Status::PAID;
    }

    public function isSent(): bool
    {
        return $this->current_status == Status::SENT;
    }

    public function isCompleted(): bool
    {
        return $this->current_status == Status::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->current_status == Status::CANCELLED;
    }

    private function addStatus($value): void
    {
        $this->statuses[] = new Status($value, time());
        $this->current_status = $value;
    }

    ##########################

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getRace(): ActiveQuery
    {
        return $this->hasOne(Race::class, ['id' => 'race_id']);
    }

    ##############################

    public function attributeLabels(){
        return [
            'created_at' => 'Дата создания',
            'current_status' => 'Текущий статус',
            'payment_method' => 'Способ оплаты',
            'payment_id' => 'ID оплаты (Яндекс.касса)',
            'cancel_reason' => 'Причина отмены оплаты',
            'user_id' => 'ID пользователя',
            'deliveryData.index' => 'Индекс',
            'delivery_index' => 'Индекс',
            'deliveryData.address' => 'Адрес',
            'delivery_address' => 'Адрес доставки',
            'customer_name' => 'Имя покупателя',
            'customer_phone' => 'Телефон покупателя',
            'track_post' => 'Трек-номер почты',
            'weight' => 'Вес',
            'user.username' => 'Имя пользователя',
            'cost' => 'Стоимость (руб.)',
        ];
    }

    public static function tableName(): string
    {
        return '{{%shop_orders}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['items'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function afterFind(): void
    {
        $this->statuses = array_map(function ($row){
            return new Status(
                $row['value'],
                $row['created_at']
            );
        }, Json::decode($this->getAttribute('statuses_json')));

        $this->customerData = new CustomerData(
            $this->getAttribute('customer_name'),
            $this->getAttribute('customer_phone')
        );

        $this->deliveryData = new DeliveryData(
            $this->getAttribute('delivery_index'),
            $this->getAttribute('delivery_address')
        );

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('statuses_json', Json::encode(array_map(function(Status $status){
            return [
                'value' => $status->value,
                'created_at' => $status->created_at,
            ];
        }, $this->statuses)));

        $this->setAttribute('customer_name', $this->customerData->name);
        $this->setAttribute('customer_phone', $this->customerData->phone);

        $this->setAttribute('delivery_index', $this->deliveryData->index);
        $this->setAttribute('delivery_address', $this->deliveryData->address);

        return parent::beforeSave($insert);
    }
}