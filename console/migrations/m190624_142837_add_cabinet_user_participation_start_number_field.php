<?php

use yii\db\Migration;

/**
 * Class m190624_142837_add_cabinet_user_participation_start_number_field
 */
class m190624_142837_add_cabinet_user_participation_start_number_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%cabinet_user_participation}}', 'start_number', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%cabinet_user_participation}}', 'start_number');
    }
}
