<?php

use yii\db\Migration;

/**
 * Class m190607_192639_add_shop_orders_race_id_field
 */
class m190607_192639_add_shop_orders_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_orders}}', 'race_id', $this->integer()->notNull());
        $this->addColumn('{{%shop_orders}}', 'delivery_city', $this->string()->after('delivery_address'));
        $this->addColumn('{{%shop_orders}}', 'weight', $this->float()->after('delivery_city'));
        $this->addColumn('{{%shop_orders}}', 'track_post', $this->string()->comment('Трек-номер посылки')->after('weight'));
        $this->addColumn('{{%shop_orders}}', 'notify_send', $this->boolean()->comment('Уведомление о напоминании оплаты')->defaultValue(0)->after('track_post'));

        $this->createIndex('{{%idx-shop_orders-race_id}}', '{{%shop_orders}}', 'race_id');
        $this->addForeignKey('{{%fk-shop-orders-race_id}}', '{{%shop_orders}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-shop-orders-race_id}}', '{{%shop_orders}}');
        $this->dropColumn('{{%shop_orders}}', 'race_id');
        $this->dropColumn('{{%shop_orders}}', 'weight');
        $this->dropColumn('{{%shop_orders}}', 'track_post');
        $this->dropColumn('{{%shop_orders}}', 'notify_send');
    }
}
