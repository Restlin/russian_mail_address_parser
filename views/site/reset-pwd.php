<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\security\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
$template = '<fieldset><legend>{label}</legend>{input}</fieldset>{error}';
?>

<div class="outer-conteiner">
    <div class="inner-conteiner site-reset-pwd">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <p>Пожалуйста, заполните форму для смены пароля:</p>
        <?= $form->field($model, 'password', ['template' => $template])->passwordInput(['class' => 'fieldin']); ?>
        <?= $form->field($model, 'password_confirm', ['template' => $template])->passwordInput(['class' => 'fieldin']); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'square pirs-btn-blue btn', 'name' => 'login-button']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
