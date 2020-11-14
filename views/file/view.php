<?php

use yii\web\View;
use app\models\File;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;
use app\models\Row;

/* @var $this View */
/* @var $model File */
/* @var $rows string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Файлы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);
?>
<div class="file-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить данные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'mime',
            [
                'attribute' => 'size',
                'value' => Yii::$app->formatter->asShortSize($model->size),
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
                'format' => 'html',
            ],
            [
                'label' => 'Количество строк',
                'value' => $model->getCountAllRows(),
            ],
            [
                'label' => 'Количество успешно обработанных строк',
                'value' => $model->getCountRowsByStatuses([Row::STATUS_DONE]),
            ],
            [
                'label' => 'Количество ошибок',
                'value' => $model->getCountRowsByStatuses([Row::STATUS_ERROR]),
            ],
        ],
    ]) ?>

    <?= $rows ?>

</div>
