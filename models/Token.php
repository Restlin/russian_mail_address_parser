<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property string $value Значение
 */
class Token extends \yii\db\ActiveRecord {

    /**
     * Токены
     * @var array
     */
    private static ?array $tokens = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'value' => 'Value',
        ];
    }

    public static function getTokens() {
        if (static::$tokens === null) {
            static::$tokens = Token::find()->select(['value'])->indexBy('value')->column();
        }
        return static::$tokens;
    }

}
