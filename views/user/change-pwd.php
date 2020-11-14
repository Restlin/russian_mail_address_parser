<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $form \app\security\ChangePwdForm */
/* @var $user \app\user\models\User */
/* @var $hash string */
/* @var $hasTasks boolean */

$template = '<fieldset><legend>{label}</legend>{input}</fieldset>{error}';

Pjax::begin([
    'id' => 'change-pwd',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'options' => [
        'class' => 'pjax-container',
    ],
]);
?>
<div class="change-pwd-container">
    <?php
    $form = ActiveForm::begin([
                'id' => 'change-pwd-form',
                'method' => 'post',
                'enableAjaxValidation' => true,
                'validationUrl' => ['/user/change-pwd-validate'],
                'action' => ['/user/change-pwd'],
                'options' => [
                    'data-pjax' => true
                ]
    ]);
    ?>
    <div id="change-pwd-form-fields">
        <?= $form->field($model, 'old_password', ['template' => $template])->passwordInput(['class' => 'fieldin']) ?>
        <?= $form->field($model, 'password', ['template' => $template])->passwordInput(['class' => 'fieldin']) ?>
        <?= $form->field($model, 'password_confirm', ['template' => $template])->passwordInput(['class' => 'fieldin']) ?>
    </div>
    <div id="message-change-pwd"></div>
    <div id="change-pwd-control">
        <?= Html::submitButton('Сменить', ['class' => 'pirs-btn-blue pirs-btn-save square pull-right']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
