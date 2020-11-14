<?php


namespace app\services;

use Yii;
use app\models\File;
use app\models\Row;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\helpers\FileHelper;
use yii\base\Exception;
use DateTime;

final class FileService extends BaseObject
{
    /**
     * Пути файлов для удаления
     * @var array|File[]
     */
    private static array $filesForDel = [];

    /**
     * Путь до папки с файлами
     * @var string
     */
    private string $path = '';

    /**
     * FileService constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (!key_exists('path', $params)) {
            throw new InvalidArgumentException();
        }
        $this->path = $params['path'];
    }

    /**
     * Проверка существования директории и создание
     * @param File $file
     * @throws Exception
     */
    public function createDir(File $file): void
    {
        $path = $this->getFileDir($file);
        if (!file_exists($path)) {
            FileHelper::createDirectory($path);
        }
    }

    /**
     * Путь до файла
     * @param File $file
     * @return string
     */
    public function getFilePath(File $file): string
    {
        return $this->getFileDir($file) . $file->id;
    }

    /**
     * Директория файла
     * @param File $file
     * @return string
     */
    public function getFileDir(File $file): string
    {
        $dir = intdiv($file->id, 1000) * 1000;
        return Yii::getAlias($this->path . $dir . '/');
    }

    /**
     * Добавление пути к файлу на удаление
     * @param File[] $files
     */
    public function addFileForDelete(array $files): void
    {
        static::$filesForDel = array_merge(static::$filesForDel, $files);
    }

    /**
     * Удаленеи файлов self::$pathsForDel
     */
    public function deletePreparedFiles(): void
    {
        foreach (static::$filesForDel as $file) {
            $file->delete();
        }
    }

    /**
     * @param File $file
     */
    public function deleteFile(File $file): void
    {
        $fp = $this->getFilePath($file);
        if (file_exists($fp)) {
            unlink($fp);
        }
    }

    public function recalcFile(File $file): void {
        $hasRowsForWork = false;
        foreach($file->rows as $row) {
            if(in_array($row->status, [Row::STATUS_WORK, Row::STATUS_NONE])) {
                $hasRowsForWork = true;
                break;
            }
        }
        $file->status = $hasRowsForWork ? File::STATUS_WORK : File::STATUS_DONE;
        if ($file->status == File::STATUS_DONE) {
            $file->date_end = (new DateTime())->format('d.m.Y H:i:s');
        }
        $file->save();
    }
}
