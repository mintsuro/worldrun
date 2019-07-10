<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_product}}`.
 */
class m190511_155219_create_shop_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'price' => $this->integer()->notNull(),
            'quantity' => $this->integer(),
            'photo' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%shop_products}}');
    }
}
