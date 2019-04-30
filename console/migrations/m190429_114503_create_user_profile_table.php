<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profile}}`.
 */
class m190429_114503_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50),
            'sex' => $this->string(10),
            'age' => $this->smallInteger(),
            'postal_code' => $this->integer(15),
            'address_delivery' => $this->string(100),
            'size_costume' => $this->string(10),
            'user_id' => $this->integer(255)->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-user_profile_user_id}}', '{{%user_profile}}', 'user_id');
        $this->addForeignKey('{{%fk-user_profile_user_id}}', '{{%user_profile}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk-user_profile_user_id}}', '{{%user_profile}}');
        $this->dropTable('{{%user_profile}}');
    }
}
