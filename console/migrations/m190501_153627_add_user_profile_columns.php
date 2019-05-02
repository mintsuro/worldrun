<?php

use yii\db\Migration;

/**
 * Class m190501_153627_add_user_profile_columns
 */
class m190501_153627_add_user_profile_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%user_profile}}', 'city', $this->string(50)->after('age'));
        $this->addColumn('{{%user_profile}}', 'phone', $this->string(30)->after('city'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%user_profile}}', 'city');
        $this->dropColumn('{{%user_profile}}', 'phone');
    }
}
