<?php

namespace app\security;

use app\models\User;
use Yii;
use yii\base\Model;

class ChangePwdForm extends Model {

    /**
     * Пользователь
     * @var User|null
     */
    public ?User $user = null;

    /**
     * Пароль
     * @var string|null
     */
    public ?string $old_password = null;

    /**
     * Новый пароль
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
            [['password', 'password_confirm', 'old_password'], 'required'],
            [['password'], 'string', 'min' => 6, 'max' => 50],
            [['password_confirm'], 'string'],
            [['password_confirm'], 'validatePasswordConfirm'],
            [['old_password'], 'validateOldPassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOldPassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $identity = Yii::$app->user->getIdentity();
            if ($identity) {
                $this->user = $identity->getUser();
            }
            if (!$identity->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Неверный пароль!');
            }
        }
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
            'old_password' => 'Текущий пароль',
            'password' => 'Новый пароль',
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
            return $this->user->save();
        }
        return false;
    }

}
