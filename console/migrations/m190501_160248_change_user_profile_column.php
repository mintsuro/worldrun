<?php

use yii\db\Migration;

/**
 * Class m190501_160248_change_user_profile_column
 */
class m190501_160248_change_user_profile_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('{{%user_profile}}', 'age', $this->string(10));
        $this->alterColumn('{{%user_profile}}', 'sex', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->alterColumn('{{%user_profile}}', 'age', $this->integer());
        $this->alterColumn('{{%user_profile}}', 'sex', $this->string(10));
    }
}
