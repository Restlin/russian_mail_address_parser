<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

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

}
