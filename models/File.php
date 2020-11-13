<?php

namespace app\models;

use app\services\FileService;
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
    /**
     * статус ожидания обработки
     */
    const STATUS_WAIT = 0;
    /**
     * статус обработан
     */
    const STATUS_COMPLETE = 1;
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

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // TODO вынести в EventDispatcher
        $container = Yii::$container;
        try {
            $service = $container->get(FileService::class);
            $service->createDir($this);
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // TODO вынести в EventDispatcher
        $container = Yii::$container;
        try {
            $service = $container->get(FileService::class);
            $service->deleteFile($this);
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
        }
    }
}
