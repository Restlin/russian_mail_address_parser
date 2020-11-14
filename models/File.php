<?php

namespace app\models;

use app\services\FileService;
use Yii;
use yii\db\ActiveRecord;
use app\models\Row;

/**
 * This is the model class for table "file".
 *
 * @property int $id ID Файла
 * @property string $name Наименование файла
 * @property string $mime MIME тип
 * @property int $size Размер
 * @property int $status Статус обработки файла
 * 
 * @property Row[] $rows строки файла
 */
class File extends ActiveRecord
{
    const STATUS_NONE = 0;
    const STATUS_WORK = 1;
    const STATUS_DONE = 2;
    const STATUS_ERROR = 3;
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
    
    /**
     * Gets query for [[Row]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRows()
    {
        return $this->hasMany(Row::class, ['file_id' => 'id']);
    }
}
