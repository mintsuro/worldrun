<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabinet_tracks}}`.
 */
class m190520_214657_create_cabinet_tracks_table extends Migration
{

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%cabinet_tracks}}', [
            'id' => $this->primaryKey(),
            'distance' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'download_method' => $this->smallInteger()->notNull(),
            'time_race' => $this->integer()->notNull(),
            'pace' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cabinet_tracks-user_id}}', '{{%cabinet_tracks}}', 'user_id');

        $this->addForeignKey('{{%fk-cabinet_tracks-user_id}}', '{{%cabinet_tracks}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%cabinet_tracks}}');
    }
}
