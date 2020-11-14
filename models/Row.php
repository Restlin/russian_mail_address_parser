<?php

namespace app\models;

use app\models\File;
use app\services\FileService;
use Yii;

/**
 * This is the model class for table "row".
 *
 * @property int $id ИД строки
 * @property int $file_id ИД файла
 * @property string $content Содержимое строки
 * @property string|null $address_base Базовый адрес
 * @property string|null $address_new Адрес после обработки
 * @property int $status Статус обработки
 *
 * @property File $file
 */
class Row extends \yii\db\ActiveRecord
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
        return 'row';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'content'], 'required'],
            [['file_id', 'status'], 'default', 'value' => null],
            [['file_id', 'status', 'id'], 'integer'],
            [['content', 'address_base', 'address_new'], 'string', 'max' => 5000],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД строки',
            'file_id' => 'ИД файла',
            'content' => 'Содержимое строки',
            'address_base' => 'Базовый адрес',
            'address_new' => 'Адрес после обработки',
            'status' => 'Статус обработки',
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $container = Yii::$container;
        /*@var $service FileService */
        $service = $container->get(FileService::class);
        $service->recalcFile($this->file);
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete() {
        $container = Yii::$container;
        /*@var $service FileService */
        $service = $container->get(FileService::class);
        $service->recalcFile($this->file);
        parent::afterDelete();
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
    }

    /**
     * @return string[]
     */
    public static function getStatuses(): array
    {
        return [
            static::STATUS_NONE => 'В очереди',
            static::STATUS_WORK => 'В работе',
            static::STATUS_DONE => 'Обработка завершена',
            static::STATUS_ERROR => 'Ошибка обработки',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        $statuses = static::getStatuses();
        return $statuses[$this->status] ?? 'Неверный статус';
    }
}
