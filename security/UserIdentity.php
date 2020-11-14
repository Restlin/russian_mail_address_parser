<?php

namespace app\security;

use app\models\User;
use Yii;
use yii\web\IdentityInterface;

/**
 * Идентификатор пользователя для процедуры авторизации
 *
 * @author restlin
 */
class UserIdentity implements IdentityInterface {

    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User {
        return $this->user;
    }

    /**
     * Найти идентификатор пользователя по ИД
     * @param int $id ИД пользователя
     * @return UserIdentity|null
     */
    public static function findIdentity($id): ?UserIdentity {
        $user = User::findOne(['id' => $id]);
        return $user ? new self($user) : null;
    }

    public static function findIdentityByUsername(string $username): ?UserIdentity {
        $user = User::findOne(['email' => $username, 'active' => true]);
        return $user ? new self($user) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null): ?UserIdentity {
        /** @todo если пригодится, сделать */
    }

    /**
     * Получить идентификатор пользователя
     * @return int
     */
    public function getId(): ?int {
        return $this->user ? $this->user->id : null;
    }

    /**
     * Получить имя пользователя
     * @return string
     */
    public function getUsername(): ?string {
        return $this->user ? $this->user->email : null;
    }

    public function getAuthKey(): ?string {
        //@todo добавить поле в пользователя
        return null;
    }

    public function validateAuthKey($authKey): bool {
        //@todo добавить поле в пользователя
        return false;
    }

    /**
     * Проверка корректности пароля
     * @param string $password пароль
     * @return bool
     */
    public function validatePassword(string $password): bool {
        if (mb_strlen($this->user->password_hash) === 34) { // const for length wordpress password hash
            return Yii::$app->securityWordPress->validatePassword($password, $this->user->password_hash);
        }
        return Yii::$app->security->validatePassword($password, $this->user->password_hash);
    }

}
