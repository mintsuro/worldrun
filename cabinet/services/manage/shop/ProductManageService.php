<?php

namespace cabinet\services\manage\shop;

use cabinet\entities\shop\product\Product;
use cabinet\forms\manage\shop\product\ProductForm;
use cabinet\repositories\shop\ProductRepository;
use cabinet\services\TransactionManager;
use cabinet\forms\manage\shop\product\QuantityForm;

class ProductManageService
{
    private $products;
    private $transaction;

    public function __construct(
        ProductRepository $products,
        TransactionManager $transaction
    )
    {
        $this->products = $products;
        $this->transaction = $transaction;
    }

    public function create(ProductForm $form): Product
    {
        $product = Product::create(
            $form->name,
            $form->description,
            $form->price,
            $form->sort,
            $form->race_id
        );

        if ($form->photo) {
            $product->setPhoto($form->photo);
        }

        $this->products->save($product);

        return $product;
    }

    public function edit($id, ProductForm $form): void
    {
        $product = $this->products->get($id);

        $product->edit(
            $form->name,
            $form->description,
            $form->price,
            $form->sort,
            $form->race_id
        );

        if ($form->photo) {
            $product->setPhoto($form->photo);
        }

        $this->products->save($product);
    }

    public function changeQuantity($id, QuantityForm $form): void
    {
        $product = $this->products->get($id);
        $product->changeQuantity($form->quantity);
        $this->products->save($product);
    }

    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }
}