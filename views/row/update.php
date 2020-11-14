<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Row;

/* @var $this View */
/* @var $model Row */

$this->title = 'Редактирование: ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Обработанные строки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Файлы', 'url' => ['/file/index']];
$this->params['breadcrumbs'][] = ['label' => $model->file->name, 'url' => ['/file/view', 'id' => $model->file->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="row-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'address_base')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'address_new')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
