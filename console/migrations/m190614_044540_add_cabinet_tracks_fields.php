<?php

use yii\db\Migration;

/**
 * Class m190614_044540_add_cabinet_tracks_fields
 */
class m190614_044540_add_cabinet_tracks_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%cabinet_tracks}}', 'cancel_reason', $this->integer());
        $this->addColumn('{{%cabinet_tracks}}', 'cancel_text', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet_tracks}}', 'cancel_reason');
        $this->dropColumn('{{%cabinet_tracks}}', 'cancel_text');
    }
}
