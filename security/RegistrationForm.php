<?php

namespace app\security;

use app\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Форма регистрации
 * @author pky
 */
class RegistrationForm extends Model {

    /**
     * Пароль
     * @var string
     */
    public string $password = '';

    /**
     * Подтверждение пароля
     * @var string
     */
    public string $password_confirm = '';

    /**
     * Email
     * @var string
     */
    public string $email = '';

    /**
     * Телефон
     * @var string
     */
    public string $phone = '';

    /**
     * Фамилия
     * @var string
     */
    public string $surname = '';

    /**
     * Имя
     * @var string
     */
    public string $name = '';

    /**
     * Отчество
     * @var string
     */
    public string $patronymic = '';

    /**
     * Пользователь
     * @var User|null
     */
    private ?User $user = null;

    public function rules() {
        return [
            [['email', 'surname', 'name', 'password', 'password_confirm'], 'required'],
            [['email', 'surname', 'name', 'patronymic'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['phone'], PhoneInputValidator::class],
            [['password'], 'string', 'min' => 6, 'max' => 50],
            [['password_confirm'], 'string'],
            [['password_confirm'], 'validatePasswordConfirm'],
            [['email'], 'email'],
            [['email'], 'filter', 'filter' => fn($email) => mb_strtolower(trim($email), 'UTF-8')],
            [['email'], 'validateEmail'],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с таким email уже зарегистрирован!'],
            [['phone'], 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с таким номером телефона уже зарегистрирован!'],
        ];
    }

    public function validatePasswordConfirm($attribute, $params) {
        if ($this->$attribute && $this->$attribute != $this->password) {
            $this->addError('password_confirm', 'Пароли не совпадают');
        }
    }

    public function validateEmail($attribute, $params) {
        $domen = substr($this->$attribute, strrpos($this->$attribute, '@') + 1);
        if (!checkdnsrr($domen, 'ANY')) {
            $this->addError('email', 'Не корректный email');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'password' => 'Пароль',
            'password_confirm' => 'Повторить пароль',
        ];
    }

    public function register(): bool {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $this->user = $this->createUser();
            if (!$this->user->hasErrors()) {
                if ($this->sendEmail($this->user)) {
                    $transaction->commit();
                    return true;
                }
            }
            $transaction->rollBack();
            $this->addErrors($this->user->getErrors());
        }
        return false;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    private function createUser() {
        $user = new User();
        $user->email = $this->email;
        $user->no_confirm_email = $this->email;
        $user->phone = $this->phone;
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->patronymic = $this->patronymic;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->email_code = str_pad(random_int(0, 999999), 6, 0, STR_PAD_LEFT);
        $user->email_code_unixtime = time();
        $user->save();
        return $user;
    }

    /**
     * Отправка email для подтверждения email
     * @return type
     */
    private function sendEmail(User $user) {
        $userName = $user->name . ' ' . $user->surname;
        $title = 'Подтверждение email';
        $mailer = Yii::$app->mailer;
        $mailer->getView()->title = $title;
        $result = false;
        try {
            $result = $mailer->compose(
                            ['html' => 'email-confirmation-html'],
                            [
                                'userName' => $userName,
                                'code' => $user->email_code,
                                'url' => Url::to(['/user/confirm', 'id' => $user->id], true)
                            ]
                    )
                    ->setTo([$user->email => $userName])
                    ->setSubject($title)
                    ->send();
        } catch (Exception $e) {
            $user->addError('email', $e->getMessage());
        }
        return $result;
    }

}
