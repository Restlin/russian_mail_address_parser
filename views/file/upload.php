<?php

use yii\web\View;
use kartik\file\FileInput;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this View */

?>

<?= FileInput::widget([
    'name' => 'files',
    'options' => [
        'id' => 'files-upload',
        'multiple' => true,
    ],
    'pluginOptions' => [
        'uploadAsync' => false,
        'encodeUrl' => false,
        'uploadUrl' => Url::to(['/file/upload']),
        'preferIconicPreview' => true,
        'maxFilePreviewSize' => 0,
        'showUpload' => false,
        'disabledPreviewExtensions' => null,
        'hideThumbnailContent' => true,
        'fileActionSettings' => [
            'showZoom' => false,
            'showDrag' => false,
            'downloadClass' => 'btn btn-sm btn-kv btn-default btn-outline-secondary',
            'removeClass' => 'btn btn-sm btn-kv btn-default btn-outline-secondary',
        ],
        'initialPreviewAsData' => true,
        'initialPreviewFileType' => 'image',
        'overwriteInitial' => false,
        'layoutTemplates' => [
            'actionDownload' => '<a class="{downloadClass}" title="{downloadTitle}" href="{downloadUrl}" target="_blank" data-pjax="0">{downloadIcon}</a>',
        ],
        //'maxFileSize' => 102400,
        'allowedFileExtensions'=>['csv', 'xlsx'],
    ],
    'pluginEvents' => [
        'filebatchselected' => new JsExpression('function(event, files){$(this).fileinput("upload");}'),
    ],
]) ?>
