<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabinet_race}}`.
 */
class m190503_114721_create_cabinet_race_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%cabinet_race}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'photo' => $this->string(),
            'strava_only' => $this->boolean()->defaultValue(0),
            'status' => $this->integer()->notNull(),
            'date_start' => $this->integer()->notNull(),
            'date_end' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%cabinet_race}}');
    }
}
