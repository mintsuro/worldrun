<?php

use yii\db\Migration;

/**
 * Class m190518_135218_add_shop_orders_payment_id_field
 */
class m190518_135218_add_shop_orders_payment_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%shop_orders}}', 'payment_id', $this->string()->unique()->after('payment_method'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%shop_orders}}', 'payment_id');
    }
}
