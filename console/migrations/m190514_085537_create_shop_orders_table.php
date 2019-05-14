<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_orders}}`.
 */
class m190514_085537_create_shop_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_orders}}', [
            'id' => $this->primaryKey(),
            'payment_method' => $this->string(),
            'cost' => $this->integer()->notNull(),
            'current_status' => $this->integer()->notNull(),
            'cancel_reason' => $this->text(),
            'statuses_json' => 'JSON NOT NULL',
            'customer_phone' => $this->string(),
            'customer_name' => $this->string(),
            'delivery_index' => $this->string(),
            'delivery_address' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_orders-user_id}}', '{{%shop_orders}}', 'user_id');

        $this->addForeignKey('{{%fk-shop_orders-user_id}}', '{{%shop_orders}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%shop_orders}}');
    }
}
