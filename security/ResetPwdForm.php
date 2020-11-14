<?php

namespace app\security;

use app\models\User;
use Yii;
use yii\base\Model;

class ResetPwdForm extends Model {

    /**
     * Пользователь
     * @var User|null
     */
    public ?User $user = null;

    /**
     * Пароль
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Подтверждение пароля
     * @var string|null
     */
    public ?string $password_confirm = null;

    public function rules(): array {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password'], 'string', 'min' => 6, 'max' => 50],
            [['password_confirm'], 'string'],
            [['password_confirm'], 'validatePasswordConfirm'],
        ];
    }

    public function validatePasswordConfirm($attribute, $params) {
        if ($this->$attribute && $this->$attribute != $this->password) {
            $this->addError('password_confirm', 'Пароли не совпадают');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'password' => 'Пароль',
            'password_confirm' => 'Повторить пароль',
        ];
    }

    /**
     * Выставить новый пароль
     * @return bool
     */
    public function setNewPwd(): bool {
        if ($this->validate()) {
            $this->user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->user->pwd_reset_token = null;
            $this->user->pwd_reset_token_unixtime = null;
            $this->user->active = true;
            if ($this->user->save()) {
                $userService = \Yii::$container->get(\app\user\Service::class);
                $userService->changeXmppPwd($this->user);
                return true;
            }
        }
        return false;
    }

}
