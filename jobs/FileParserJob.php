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

        $file->status = File::STATUS_WORK;

        $filepath = $service->getFilePath($file);
        if(file_exists($filepath)) {
            $type = $this->getType($filepath);
            if($type == 'text/plain') { //@todo ужасный mime
                $encoding = $this->getEncoding($filepath);
                if(!$encoding) {
                    $file->status = File::STATUS_WRONG_ENCODING;
                } elseif($encoding != 'utf-8') {
                    $tmppath = "/tmp/$file->id.csv";
                    exec("iconv -f $encoding -t utf-8 $filepath -o $tmppath");
                    $filepath = $tmppath;
                }
            } elseif($type == 'application/zip' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') { //@todo ужасный mime
                $tmppath = "/tmp/$file->id.csv";
                exec("xlsx2csv -d ';' -c utf-8 $filepath $tmppath");
                $filepath = $tmppath;
            } else {
                $file->status = File::STATUS_WRONG_TYPE;
            }
        } else {
            $file->status = File::STATUS_ERROR;
        }

        if($file->status == File::STATUS_WORK && !$this->parseFile($filepath)) {
            $file->status = File::STATUS_ERROR;
        }
        return $file->save();
    }
    private function getType(string $filepath): string {
        $encoding = exec("file --mime-type $filepath");
        return str_replace($filepath.': ', '', $encoding);
    }

    private function getEncoding(string $filepath): string {
        $encoding = exec("file --mime-encoding $filepath");
        return str_replace($filepath.': ', '', $encoding);
    }

    private function parseFile(string $filepath): bool {
        $handle = fopen($filepath, "r");
        if ($handle) {
            while (($content = fgets($handle, 4096)) !== false) {
                $this->createRow($content);
            }
            fclose($handle);
            return true;
        }
        return false;
    }

    private function createRow(string $content): void {
        $row = new Row();
        $row->content = $content;
        $row->file_id = $this->fileId;
        $row->status = Row::STATUS_WORK;
        if($row->save()) {
            Yii::$app->queue->push(new RowParserJob(['rowId' => $row->id]));
        }
    }
}
