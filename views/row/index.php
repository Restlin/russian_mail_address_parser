<?php

use yii\web\View;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\RowSearch;
use yii\data\ActiveDataProvider;
use kartik\export\ExportMenu;
use restlin\pjaxindicator\PjaxIndicator as Pjax;

/* @var $this View */
/* @var $searchModel RowSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Обработанные строки';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
    </p>

    <?php Pjax::begin(['id' => 'grid-view-rows']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'file_id',
            'content:ntext',
            'address_base:ntext',
            'address_new:ntext',
            'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'row',
                'template' => '{update}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
