<?php

namespace cabinet\services\manage\shop;

use cabinet\repositories\shop\DiscountRepository;
use cabinet\forms\manage\shop\product\DiscountForm;
use cabinet\entities\shop\Discount as Discount;

class DiscountManageService
{
    private $discounts;

    public function __construct(DiscountRepository $discounts)
    {
        $this->discounts = $discounts;
    }

    public function create(DiscountForm $form): Discount
    {
        $discount = Discount::create(
            $form->name,
            $form->value,
            $form->fromDate,
            $form->toDate,
            $form->typeValue,
            $form->type,
            $form->sizeProducts,
            $form->code
        );
        $this->discounts->save($discount);
        return $discount;
    }

    public function edit($id, DiscountForm $form): void
    {
        $discount = $this->discounts->get($id);
        $discount->edit(
            $form->name,
            $form->value,
            $form->fromDate,
            $form->toDate,
            $form->typeValue,
            $form->type,
            $form->sizeProducts,
            $form->code
        );
        $this->discounts->save($discount);
    }

    public function activate($id): void
    {
        $discount = $this->discounts->get($id);
        $discount->activate();
        $this->discounts->save($discount);
    }

    public function draft($id): void
    {
        $discount = $this->discounts->get($id);
        $discount->draft();
        $this->discounts->save($discount);
    }

    public function remove($id): void
    {
        $discount = $this->discounts->get($id);
        $this->discounts->remove($discount);
    }
}