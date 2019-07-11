<?php

use yii\db\Migration;

/**
 * Class m190624_142837_add_cabinet_user_participation_start_number_field
 */
class m190624_142837_add_cabinet_user_participation_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%cabinet_user_participation}}', 'start_number', $this->integer());
        $this->addColumn('{{%cabinet_user_participation}}', 'notify_start', $this->boolean()->defaultValue(0)->comment('Отправка уведомления после старта забега'));
        $this->addColumn('{{%cabinet_user_participation}}', 'notify_end', $this->boolean()->defaultValue(0)->comment('Отправка уведомления перед завершением забега'));
        $this->addColumn('{{%cabinet_user_participation}}', 'notify_finish', $this->boolean()->defaultValue(0)->comment('Отправка уведомления после завершения забега'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%cabinet_user_participation}}', 'start_number');
        $this->dropColumn('{{%cabinet_user_participation}}', 'notify_start');
        $this->dropColumn('{{%cabinet_user_participation}}', 'notify_end');
        $this->dropColumn('{{%cabinet_user_participation}}', 'notify_finish');
    }
}
