<?php

use yii\db\Migration;

/**
 * Class m190709_110307_add_shop_products_fields
 */
class m190709_110307_add_shop_products_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'sort', $this->smallInteger()->defaultValue(1)->after('status'));
        $this->addColumn('{{%shop_products}}', 'race_id', $this->integer()->notNull());

        $this->createIndex('{{%idx-shop_products-race_id}}', '{{%shop_products}}', 'race_id');
        $this->addForeignKey('{{%fk-shop_products-race_id}}', '{{%shop_products}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE');
    }

    public function down()
    {
       $this->dropForeignKey('{{%fk-shop_products-race_id}}', '{{%shop_products}}');
       $this->dropColumn('{{%shop_products}}', 'sort');
       $this->dropColumn('{{%shop_products}}', 'race_id');
    }
}
