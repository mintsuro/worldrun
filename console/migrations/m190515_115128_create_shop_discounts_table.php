<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_discounts}}`.
 */
class m190515_115128_create_shop_discounts_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_discounts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'value' => $this->integer()->notNull(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'active' => $this->boolean()->notNull(),
            'sort' => $this->integer()->notNull(),
            'type_value' => $this->integer()->notNull()->comment('Тип значения скидки (проценты или число)'),
            'type' => $this->integer()->notNull()->comment('Тип скидки (скидка на выбранное кол-во товаров или промокод'),
            'size_products' => $this->integer()->comment('Количество выбранных товаров для которых действует скидка'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%shop_discounts}}');
    }
}
