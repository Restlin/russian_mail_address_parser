<?php

namespace app\jobs;

use app\models\Row;
use Yii;
use app\services\RowService;

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
        $service = Yii::$container->get(RowService::class);
        $service->responseRow($row);
    }

}
