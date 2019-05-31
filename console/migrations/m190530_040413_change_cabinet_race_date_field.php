<?php

use yii\db\Migration;

/**
 * Class m190530_040413_change_cabinet_race_date_field
 */
class m190530_040413_change_cabinet_race_date_field extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%cabinet_race}}', 'date_start', $this->dateTime()->notNull());
        $this->alterColumn('{{%cabinet_race}}', 'date_end', $this->dateTime()->notNull());
    }

    public function down()
    {
        $this->alterColumn('{{%cabinet_race}}', 'date_start', $this->integer()->notNull());
        $this->alterColumn('{{%cabinet_race}}', 'date_end', $this->integer()->notNull());
    }
}
