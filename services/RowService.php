<?php

namespace app\services;

use app\helpers\AddressHelper;
use app\models\Row;
use app\models\Token;
use Yii;

class RowService {

    public function responseRow(Row $row) {
        if ($row->address_base === null) {
            $addressParts = Token::getTokens();
            $row->address_base = AddressHelper::findAddress($row->content, $addressParts);
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
