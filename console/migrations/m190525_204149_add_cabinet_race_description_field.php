<?php

use yii\db\Migration;

/**
 * Class m190525_204149_add_cabinet_race_description_field
 */
class m190525_204149_add_cabinet_race_description_field extends Migration
{

    public function up()
    {
        $this->addColumn('{{%cabinet_race}}', 'description', 'text');
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet_race}}', 'description');
    }
}
