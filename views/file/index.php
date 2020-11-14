<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use app\models\FileSearch;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use restlin\pjaxindicator\PjaxIndicator as Pjax;
use kartik\export\ExportMenu;

/* @var $this View */
/* @var $searchModel FileSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $uploadForm string */

$this->title = 'Файлы';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('#modal-upload-files').on('hidden.bs.modal', function (e) {
        $.pjax.reload({container: '#grid-view-files'});
    });
");
?>
<div class="file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    <?php
    Modal::begin([
        'id' => 'modal-upload-files',
        'header' => '<h3>Загрузка файлов</h3>',
        'toggleButton' => ['label' => 'Загрузить файлы', 'class' => 'btn btn-success'],
    ]);

    echo $uploadForm;

    Modal::end();
    ?>

    </p>

    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'exportConfig' => [
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_PDF => false,
            ExportMenu::FORMAT_EXCEL => false,
            /*ExportMenu::FORMAT_CSV => false,*/
            ExportMenu::FORMAT_EXCEL_X => [
                'label' => 'XLSX',
            ],
        ],
    ]); ?>

    <?php Pjax::begin(['id' => 'grid-view-files']); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => yii\grid\SerialColumn::class
            ],
            'name',
            'size',
            'status',
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view} {delete}'
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
