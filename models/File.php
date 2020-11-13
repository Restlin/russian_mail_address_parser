<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property int $id ID Файла
 * @property string $name Наименование файла
 * @property string $mime MIME тип
 * @property int $size Размер
 * @property int $status Статус обработки файла
 */
class File extends ActiveRecord
{
    const STATUS_NONE = 0;
    const STATUS_WORK = 1;
    const STATUS_DONE = 2;
    const STATUS_ERROR = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mime'], 'required'],
            [['size', 'status'], 'default', 'value' => null],
            [['size', 'status'], 'integer'],
            [['name', 'mime'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Файла',
            'name' => 'Наименование файла',
            'mime' => 'MIME тип',
            'size' => 'Размер',
            'status' => 'Статус обработки файла',
        ];
    }
}
