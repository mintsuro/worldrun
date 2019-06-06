<?php

use yii\db\Migration;

/**
 * Class m190605_092354_create_cabinet_race_pdf_template
 */
class m190605_092354_create_cabinet_race_pdf_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%cabinet_race_pdf_template}}', [
            'id' => $this->primaryKey(),
            'diploma' => $this->string()->notNull(),
            'start_number' => $this->string()->notNull(),
            'diploma_top' => $this->string(),
            'start_number_top' => $this->string(),
            'race_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cabinet_race_pdf_template-race_id}}', '{{%cabinet_race_pdf_template}}', 'race_id');
        $this->addForeignKey('{{%fk-cabinet_race_pdf_template-race_id}}', '{{%cabinet_race_pdf_template}}', 'race_id', '{{%cabinet_race}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%cabinet_race_pdf_template}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190605_092354_create_cabinet_race_pdf_template cannot be reverted.\n";

        return false;
    }
    */
}
