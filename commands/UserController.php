<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Row;
use app\models\User;
use app\services\PostApiService;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserController extends Controller {

    public function actionCreateAdmin() {
        $params = Yii::$app->params;
        if (!isset($params['adminUser'])) {
            throw new Exception('Не заполнена конфигурация пользователя admin!');
        }
        $user = User::findOne(['email' => $params['adminUser']['email']]);
        if (!$user) {
            $user = new User();
        }
        $user->setAttributes($params['adminUser']);
        $user->password_hash = Yii::$app->security->generatePasswordHash($params['adminUser']['password']);
        $user->save();
    }

    public function actionTest() {
        $rows = Row::find()->where(['is not', 'address_base', null])->all();
        foreach ($rows as $row) {
            /* @var $service PostApiService */
            $service = Yii::$container->get(PostApiService::class);
            $data = $service->checkAddress($row);
            if ($this->cheskData($data)) {
                $row->address_new = $data['addr']['outaddr'];
                $row->status = Row::STATUS_DONE;
            } else {
                $row->status = Row::STATUS_ERROR;
            }
            $row->save();
        }
    }

    protected function cheskData(array $data): bool {
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
