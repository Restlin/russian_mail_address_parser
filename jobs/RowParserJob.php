<?php

namespace app\jobs;

use app\models\Row;

/**
 * Разбор строки
 *
 * @author restlin
 */
class RowParserJob extends \yii\base\BaseObject implements \yii\queue\JobInterface {
    /**
     * ИД строки
     * @var int
     */
    public $rowId;
    public function execute($queue) {
        $row = Row::findOne($this->rowId);
        if(!$row) {
            return false;
        }
        $row->status = Row::STATUS_DONE;
        $row->save();
    }
}
