<?php

use yii\db\Migration;

/**
 * Class m190520_194623_add_shop_discounts_field
 */
class m190520_194623_add_shop_discounts_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_discounts}}', 'code', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%shop_discounts}}', 'code');
    }
}
