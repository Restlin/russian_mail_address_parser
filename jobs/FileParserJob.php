<?php

namespace app\jobs;
use app\models\File;
use app\models\Row;
use app\services\FileService;
use Yii;

/**
 * Разбор файла в строки
 *
 * @author restlin
 */
class FileParserJob  extends \yii\base\BaseObject implements \yii\queue\JobInterface {
    /**
     * ИД файла
     * @var int
     */
    public $fileId;
    public function execute($queue) {
        $file = File::findOne($this->fileId);        
        if(!$file) {
            return false;
        }
        $container = Yii::$container;
        /*@var $service \app\services\FileService */
        $service = $container->get(FileService::class);
        
        $filepath = $service->getFilePath($file);
        if(!file_exists($filepath)) {
            $file->status = File::STATUS_ERROR;
            $file->save();            
            return false;
        }
        $handle = fopen($filepath, "r");
        if ($handle) {
            while (($content = fgets($handle, 4096)) !== false) {                
                $this->createRow($content);
            }            
            fclose($handle);
            $file->status = File::STATUS_WORK;
            $file->save();
        }
        return true;
    }
    
    private function createRow(string $content) {
        $row = new Row();
        $row->content = $content;
        $row->file_id = $this->fileId;
        $row->status = Row::STATUS_WORK;
        if($row->save()) {
            Yii::$app->queue->push(new RowParserJob(['rowId' => $row->id]));
        }        
    }
}
