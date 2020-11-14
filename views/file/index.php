<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use app\models\FileSearch;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use restlin\pjaxindicator\PjaxIndicator as Pjax;

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

    <?php
        Modal::begin([
            'id' => 'modal-upload-files',
            'header' => '<h3>Загрузка файлов</h3>',
            'toggleButton' => ['label' => 'Загрузить файлы', 'class' => 'btn btn-success'],
        ]);

        echo $uploadForm;

        Modal::end();
    ?>

    <?php Pjax::begin(['id' => 'grid-view-files']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'size',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
