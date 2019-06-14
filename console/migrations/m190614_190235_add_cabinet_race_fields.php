<?php

use yii\db\Migration;

/**
 * Class m190614_190235_add_cabinet_race_fields
 */
class m190614_190235_add_cabinet_race_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%cabinet_race}}', 'date_reg_from', $this->dateTime()->comment('Начало даты регистрации')->after('date_end'));
        $this->addColumn('{{%cabinet_race}}', 'date_reg_to', $this->dateTime()->comment('Конец даты регистрации')->after('date_reg_from'));
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet_race}}', 'date_reg_from');
        $this->dropColumn('{{%cabinet_race}}', 'date_reg_to');
    }
}
