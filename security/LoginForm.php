<?php

namespace app\security;

use app\security\UserIdentity;
use Yii;
use yii\base\Model;

/**
 * Форма входа пользователя
 */
class LoginForm extends Model {

    /**
     * Имя пользователя
     * @var string
     */
    public ?string $username = null;

    /**
     * Пароль пользователя
     * @var string
     */
    public ?string $password = null;

    /**
     * Запомнить пользователя после авторизации
     * @var bool
     */
    public bool $rememberMe = true;

    /**
     * Идентификатор пользователя
     * @var UserIdentity
     */
    private $userIdentity = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'trim'],
            [['username'], 'filter', 'filter' => fn($username) => mb_strtolower(trim($username), 'UTF-8')],
            [['username'], 'email', 'message' => 'Необходимо ввести ваш Email, а НЕ логин!'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();

            if (!$identity) {
                $this->addError('username', 'Пользователь с таким email не найден на сервисе!');
            } elseif (!$identity->getUser()->active) {
                $this->addError('username', 'Профиль является неподтвержденным. Для того, чтобы восстановить доступ к профилю задайте новый пароль по кнопке "Забыли пароль?"!');
            } elseif (!$identity->validatePassword($this->password)) {
                $this->addError($attribute, 'Вы ввели неверный пароль!');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'username' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getIdentity(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Поиск интерфейса идентификации по имени пользователя
     * @return UserIdentity|null
     */
    public function getIdentity(): ?UserIdentity {
        if ($this->userIdentity === false) {
            $this->userIdentity = UserIdentity::findIdentityByUsername($this->username);
        }
        return $this->userIdentity;
    }

}
