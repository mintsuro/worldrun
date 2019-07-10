<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveRecord;

/**
 * @property string  $start_number
 * @property string  $diploma
 * @property string  $diploma_top
 * @property integer $race_id
 */
class PdfTemplate extends ActiveRecord
{
    public static function create(string $start_number, string $diploma,
        string $top_diploma): self
    {
        $item = new self();
        $item->start_number = $start_number;
        $item->diploma = $diploma;
        $item->diploma_top = $top_diploma;

        return $item;
    }

    public function edit($start_number, $diploma, $top_diploma, $raceId)
    {
        $this->start_number = $start_number;
        $this->diploma = $diploma;
        $this->diploma_top = $top_diploma;
        $this->race_id = $raceId;
    }

    public static function tableName()
    {
        return '{{%cabinet_race_pdf_template}}';
    }
}