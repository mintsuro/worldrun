<?php

use yii\db\Migration;

/**
 * Class m190710_171203_add_user_profile_city_delivery_field
 */
class m190710_171203_add_user_profile_city_delivery_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_profile}}', 'city_delivery', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%user_profile}}', 'city_delivery');
    }
}
