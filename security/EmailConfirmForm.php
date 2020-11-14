<?php

namespace app\security;

use app\models\User;
use Yii;
use yii\base\Model;

class EmailConfirmForm extends Model {

    /**
     * Код
     * @var string
     */
    public ?string $code = null;

    /**
     * Пользователь
     * @var User
     */
    public ?User $user = null;

    public function rules(): array {
        return [
            [['code'], 'string'],
            [['code'], 'trim'],
        ];
    }

    public function attributeLabels() {
        return[
            'code' => 'Код'
        ];
    }

    public function beforeValidate() {
        if ($this->user->email_code != $this->code) {
            $this->addError('code', 'Не корректный код для подтверждения email.');
        }
        if (time() > $this->user->email_code_unixtime + Yii::$app->params['tokenLive']) {
            $this->addError('code', 'Срок действия кода для подтверждения email истёк.');
        }
        return parent::beforeValidate();
    }

    /**
     * Подтвержение email
     * @return boolean
     */
    public function confirm() {
        if ($this->validate()) {
            $this->user->email_code = null;
            $this->user->email_code_unixtime = null;
            $this->user->active = true;
            $this->user->email = $this->user->no_confirm_email;
            $this->user->no_confirm_email = null;
            return $this->user->save();
        }
        return false;
    }

}
