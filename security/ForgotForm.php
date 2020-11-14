<?php

namespace app\security;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

class ForgotForm extends Model {

    /**
     * email
     * @var string
     */
    public string $email = '';

    /**
     * Пользователь
     */
    protected ?User $user = null;

    public function rules() {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function beforeValidate() {
        $this->user = User::findOne(['email' => $this->email]);
        if (!$this->user) {
            $this->addError('email', 'Пользователь с указанным email не зарегистрирован.');
        }
        return parent::beforeValidate();
    }

    public function restore(): bool {
        if ($this->validate()) {
            $this->user->pwd_reset_token = Yii::$app->security->generateRandomString();
            $this->user->pwd_reset_token_unixtime = time();
            if ($this->user->save()) {
                $this->sendRestoreEmail($this->user);
                return true;
            }
        }
        return false;
    }

    /**
     * Отправка email для подтверждения email
     */
    private function sendRestoreEmail(User $user) {
        $userName = $user->name . ' ' . $user->surname;

        $title = 'Сброс пароля';
        $mailer = Yii::$app->mailer;
        $mailer->getView()->title = $title;
        return $mailer->compose(
                                ['html' => 'password-reset-token-html'],
                                [
                                    'userName' => $userName,
                                    'resetURL' => Html::a('Сброс', Url::to(['/site/reset-pwd', 'token' => $user->pwd_reset_token], true))
                                ]
                        )
                        ->setTo([$user->email => $userName])
                        ->setSubject($title)
                        ->send();
    }

}
