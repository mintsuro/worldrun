<?php

use yii\db\Migration;

/**
 * Class m190507_085226_cabinet_user_participation
 */
class m190507_085226_create_cabinet_user_participation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%cabinet_user_participation}}', [
            'race_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-cabinet_user_participation}}', '{{%cabinet_user_participation}}', ['race_id', 'user_id']);

        $this->createIndex('{{%idx-cabinet_user_participation-race_id}}', '{{%cabinet_user_participation}}', 'race_id');
        $this->createIndex('{{%idx-cabinet_user_participation-user_id}}', '{{%cabinet_user_participation}}', 'user_id');

        $this->addForeignKey('{{%fk-cabinet_user_participation-race_id}}', '{{%cabinet_user_participation}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-cabinet_user_participation-user_id}}', '{{%cabinet_user_participation}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%cabinet_user_participation}}');
    }
}
