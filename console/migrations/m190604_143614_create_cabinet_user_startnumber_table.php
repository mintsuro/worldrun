<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabinet_user_startnumber}}`.
 */
class m190604_143614_create_cabinet_user_startnumber_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%cabinet_user_startnumber}}', [
            'id' => $this->primaryKey(),
            'race_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('{{%fk-cabinet_user_startnumber-race_id}}', '{{%cabinet_user_startnumber}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-cabinet_user_startnumber-user_id}}', '{{%cabinet_user_startnumber}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%cabinet_user_startnumber}}');
    }
}
