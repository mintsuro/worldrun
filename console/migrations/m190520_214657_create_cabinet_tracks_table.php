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
            'pace' => $this->float(),
            'elapsed_time' => $this->integer()->comment('Длительность пробежки'),
            'download_method' => $this->smallInteger()->notNull(),
            'date_start' => $this->dateTime()->notNull()->comment('Дата старта пробежки'),
            'created_at' => $this->integer()->notNull()->comment('Дата-время загрузки'),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'id_strava_track' => $this->bigInteger()->unique(),
            'user_id' => $this->integer()->notNull(),
            'race_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cabinet_tracks-user_id}}', '{{%cabinet_tracks}}', 'user_id');
        $this->createIndex('{{%idx-cabinet_tracks-race_id}}', '{{%cabinet_tracks}}', 'race_id');

        $this->addForeignKey('{{%fk-cabinet_tracks-user_id}}', '{{%cabinet_tracks}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-cabinet_tracks-race_id}}', '{{%cabinet_tracks}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%cabinet_tracks}}');
    }
}
