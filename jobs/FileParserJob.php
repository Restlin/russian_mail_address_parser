<?php

namespace app\jobs;
use app\models\File;
use app\models\Row;
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
        /*$file = File::findOne($this->fileId);
        if($file) {
            $filepath = Yii::getAlias("@app/files/{$file->id}.csv");
        }*/
        $file = new File();
        //@todo заменить когда будет файл
        $filepath = Yii::getAlias('@runtime/example_2.csv');        
        $handle = fopen($filepath, "r");
        if ($handle) {
            while (($content = fgets($handle, 4096)) !== false) {
                $this->createRow($content);
            }            
            fclose($handle);
            $file->status = File::STATUS_WORK;
            $file->save();
        }
    }
    private function createRow(string $content) {
        $row = new Row();
        $row->content = $content;
        $row->file_id = $this->fileId;
        $row->status = Row::STATUS_NONE;
        $row->save();       
    }
}
