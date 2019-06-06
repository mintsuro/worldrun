<?php

use yii\db\Migration;

/**
 * Class m190605_150040_add_cabiner_race_type_field
 */
class m190605_150040_add_cabinet_race_type_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%cabinet_race}}', 'type', $this->smallInteger()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet_race}}', 'type');
    }
}
