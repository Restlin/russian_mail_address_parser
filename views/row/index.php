<?php

use yii\web\View;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\RowSearch;
use yii\data\ActiveDataProvider;
use kartik\export\ExportMenu;
use restlin\pjaxindicator\PjaxIndicator as Pjax;
use app\models\Row;

/* @var $this View */
/* @var $searchModel RowSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Обработанные строки';
//$this->params['breadcrumbs'][] = $this->title;
$clone = clone $dataProvider;
$clone->pagination = false;
?>
<div class="row-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=
        ExportMenu::widget([
            'dataProvider' => $clone,
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_EXCEL => false,
                /* ExportMenu::FORMAT_CSV => false, */
                ExportMenu::FORMAT_EXCEL_X => [
                    'label' => 'XLSX',
                //'batchSize' => $dataProvider->pagination->pageSize,
                ],
            ],
        ]);
        ?>
    </p>

    <?php Pjax::begin(['id' => 'grid-view-rows']); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'content:ntext',
            'address_base:ntext',
            'address_new:ntext',
            [
                'attribute' => 'status',
                'filter' => Row::getStatuses(),
                'value' => fn(Row $model) => $model->getStatusName(),
                'format' => 'html',
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'controller' => 'row',
                'template' => '{resend} {update}',
                'buttons' => [
                    'resend' => function($url, Row $model, $key) {
                        return Html::a('', ['/row/resend', 'id' => $model->id], ['class' => 'glyphicon glyphicon-repeat']);
                    },
                ]
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
