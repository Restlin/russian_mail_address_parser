<?php

use app\security\RegistrationForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model RegistrationForm */


$this->title = 'Регистрация';
?>

<div class="outer-conteiner">
    <div class="inner-conteiner site-reg-conteiner">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        $form = ActiveForm::begin(['id' => 'registration-form']);
        ?>
        <?= $form->field($model, 'surname')->textInput()->label($model->getAttributeLabel('surname') . ' *') ?>
        <?= $form->field($model, 'name')->textInput()->label($model->getAttributeLabel('name') . ' *') ?>
        <?= $form->field($model, 'patronymic')->textInput() ?>
        <?= $form->field($model, 'email')->textInput()->label('Email *') ?>
        <?=
        $form->field($model, 'phone')->widget(PhoneInput::class, [
            'jsOptions' => [
                'preferredCountries' => ['ru', 'kz', 'by', 'md', 'az', 'tm', 'tj', 'ua']
            ],
            'options' => [
                'placeholder' => ' ',
                'class' => 'fieldin',
                'autocomplete' => 'new-password'
            ]
        ])->label('Номер телефона *')
        ?>
        <?= $form->field($model, 'password')->passwordInput()->label($model->getAttributeLabel('password') . ' *') ?>
        <?= $form->field($model, 'password_confirm')->passwordInput()->label($model->getAttributeLabel('password_confirm') . ' *') ?>
    </div>
    <div class="agreement">
        Поля, отмеченные звездочкой (*), обязательны для заполнения.
    </div>
    <div class="column">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
    </div>
    <div class="column">
        <?= Html::a('У меня уже есть аккаунт, войти под ним', ['/site/login']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
