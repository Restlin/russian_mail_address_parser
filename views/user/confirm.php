<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model RegistrationForm */

use app\security\RegistrationForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Подтверждение email';
$template = '<fieldset><legend>{label}</legend>{input}</fieldset>{error}';
?>
<div class="outer-conteiner">
    <div class="inner-conteiner site-reset-pwd">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>На указанный Вами email адрес было выслано письмо с кодом подтверждения. Пожалуйста, введите его:</p>
        <?php $form = ActiveForm::begin(['id' => 'confirm-form']); ?>
        <?= $form->field($model, 'code', ['template' => $template])->textInput(['autofocus' => true, 'class' => 'fieldin']); ?>
        <div class="form-group">
            <?= Html::submitButton('Подтвердить', ['class' => 'square pirs-btn-blue btn', 'name' => 'confirm-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
