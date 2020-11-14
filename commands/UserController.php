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
        $postApiService = new PostApiService();
        foreach ($rows as $row) {
            if ($row->address_base) {
                $data = $postApiService->checkAddress($row);
                var_dump($data['state']);
                if (key_exists('missing', $data['addr'])) {
                    var_dump($data['addr']['missing']);
                }
                if (key_exists('accuracy', $data['addr'])) {
                    var_dump($data['addr']['accuracy']);
                }
            }
        }
    }

}
