<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_strava}}`.
 */
class m190528_174007_create_user_strava_table extends Migration
{

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_strava}}', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cabinet_user_strava-user_id}}', '{{%user_strava}}', 'user_id');

        $this->addForeignKey('{{%fk-cabinet_user_strava-user_id}}', '{{%user_strava}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_strava}}');
    }
}
