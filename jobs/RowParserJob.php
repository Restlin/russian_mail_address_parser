<?php

namespace app\jobs;

use app\models\Row;
use app\services\PostApiService;
use app\helpers\AddressHelper;
use Yii;

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
        $addressParts = \app\models\Token::getTokens(); //@todo сюда получить словарик токенов для адреса
        $row->address_base = AddressHelper::findAddress($row->content, $addressParts);
        if (!$row || $row->address_base === null) {
            return false;
        }
        /* @var $service PostApiService */
        $service = Yii::$container->get(PostApiService::class);
        $data = $service->checkAddress($row);
        if ($this->checkData($data)) {
            $row->address_new = $data['addr']['outaddr'];
            $row->status = Row::STATUS_DONE;
        } else {
            $row->status = Row::STATUS_ERROR;
        }
        $row->save();
    }

    protected function checkData(array $data): bool {
        $result = false;
        $state = null;
        if (key_exists('state', $data)) {
            $state = $data['state'];
        }
        $missing = null;
        $accuracy = null;
        if (key_exists('addr', $data)) {
            if (key_exists('missing', $data['addr'])) {
                $missing = ($data['addr']['missing']);
            }
            if (key_exists('accuracy', $data['addr'])) {
                $accuracy = ($data['addr']['accuracy']);
            }
        }

        if (in_array($state, ['301', '302']) && $accuracy && $accuracy < 300 && ($missing === null || in_array($missing, ['F', 'NF']))) {
            $result = true;
        } elseif (($missing === null && $accuracy && $accuracy > 300) || ($accuracy && in_array($missing, ['SNF', 'TSNF']) )) {
            $result = false;
        }
        return $result;
    }

}
