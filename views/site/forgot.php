<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление пароля';
?>
<div class="outer-conteiner">
    <div class="inner-conteiner form-forgot">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Укажите ваш email:</p>
        <?php
        $form = ActiveForm::begin(['id' => 'login-form']);
        ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'form-control square'])->label(false); ?>
        <div class="form-group">
            <?= Html::submitButton('Восстановить', ['class' => 'square pirs-btn-blue btn', 'name' => 'login-button']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
