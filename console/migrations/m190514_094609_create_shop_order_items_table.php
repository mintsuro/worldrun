<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_items}}`.
 */
class m190514_094609_create_shop_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer(),
            'product_name' => $this->string()->notNull(),
            'price' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_order_items-order_id}}', '{{%shop_order_items}}', 'order_id');
        $this->createIndex('{{%idx-shop_order_items-product_id}}', '{{%shop_order_items}}', 'product_id');

        $this->addForeignKey('{{%fk-shop_order_items-order_id}}', '{{%shop_order_items}}', 'order_id', '{{%shop_orders}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-shop_order_items-product_id}}', '{{%shop_order_items}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%shop_order_items}}');
    }
}
