<?php

namespace app\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id ИД
 * @property string $surname Фамилия
 * @property string $name Имя
 * @property string|null $patronymic Отчество
 * @property string $phone Телефон
 * @property string $email Email
 * @property string|null $no_confirm_email Не подтверждённый email
 * @property string|null $email_code Код подтверждения Email
 * @property int|null $email_code_unixtime Время генерации кода
 * @property string|null $password_hash Хеш пароля
 * @property string|null $pwd_reset_token Токен для сброса пароля
 * @property int|null $pwd_reset_token_unixtime Время жизни токена сброса пароля
 * @property bool $active Активирован
 * @property bool $isAdmin Признак администратора
 */
class User extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['surname', 'name', 'email'], 'required'],
            [['email_code_unixtime', 'pwd_reset_token_unixtime'], 'integer'],
            [['active', 'isAdmin'], 'boolean'],
            [['surname', 'name', 'patronymic', 'email', 'no_confirm_email'], 'string', 'max' => 50],

            [['phone'], 'filter', 'filter' => fn($value) => $value ?: null],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'unique'],
            [['phone'], PhoneInputValidator::class],

            [['email_code'], 'string', 'max' => 6],
            [['password_hash'], 'string', 'max' => 64],
            [['pwd_reset_token'], 'string', 'max' => 32],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ИД',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'phone' => 'Телефон',
            'email' => 'Email',
            'no_confirm_email' => 'Не подтверждённый email',
            'email_code' => 'Код подтверждения Email',
            'email_code_unixtime' => 'Время генерации кода',
            'password_hash' => 'Хеш пароля',
            'pwd_reset_token' => 'Токен для сброса пароля',
            'pwd_reset_token_unixtime' => 'Время жизни токена сброса пароля',
            'active' => 'Активирован',
            'isAdmin' => 'Признак администратора',
        ];
    }

}
