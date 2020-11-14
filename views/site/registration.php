<?php

use app\security\RegistrationForm;
use app\user\repositories\UserRepository;
use borales\extensions\phoneInput\PhoneInput;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model RegistrationForm */

$template = '<fieldset><legend>{label}</legend>{input}</fieldset>{error}';
$this->title = 'Регистрация';

$agreement = Modal::widget([
            'id' => 'agreement-modal',
            'toggleButton' => [
                'label' => 'Пользовательским соглашением',
                'tag' => 'a',
                'data-target' => '#agreement-modal',
                'href' => Url::toRoute(['site/agreement']),
            ],
            'clientOptions' => false,
        ]);

$policy = Modal::widget([
            'id' => 'policy-modal',
            'toggleButton' => [
                'label' => 'Политикой конфиденциальности',
                'tag' => 'a',
                'data-target' => '#policy-modal',
                'href' => Url::toRoute(['site/policy']),
            ],
            'clientOptions' => false,
        ]);

$css = $this->assetManager->publish(__DIR__ . '/css/registration.css');
$this->registerCssFile($css[1]);
?>

<div class="outer-conteiner">
    <div class="inner-conteiner site-reg-conteiner">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        $form = ActiveForm::begin(['id' => 'registration-form']);
        ?>
        <div class="column">
            <?= $form->field($model, 'surname', ['template' => $template])->textInput(['class' => 'fieldin'])->label($model->getAttributeLabel('surname') . ' *') ?>
            <?= $form->field($model, 'name', ['template' => $template])->textInput(['class' => 'fieldin'])->label($model->getAttributeLabel('name') . ' *') ?>
            <div class="sex-radio">
                <?=
                $form->field($model, 'sex')->radioList($sexs, [
                    'class' => 'pirs-radio',
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return'<label class="radio-label radio-inline' . ($checked ? ' active' : '') . '">' . Html::radio($name, $checked, ['value' => $value]) . '<span>' . $label . '</span></label>';
                    },
                ])->label(false)
                ?>
            </div>
            <?= $form->field($model, 'type', ['template' => $template])->dropDownList($types, ['class' => 'fieldin']) ?>
            <?=
            $form->field($model, 'skillIds', ['template' => $template])->widget(Select2::class, [
                'data' => ArrayHelper::map($skills, 'id', 'name'),
                'showToggleAll' => false,
                'pluginOptions' => [
                    'maximumSelectionLength' => UserRepository::MAX_SKILLS,
                    'allowClear' => true,
                    'multiple' => true,
                    'height' => 60,
                ],
                'options' => [
                    'options' => array_map(function ($val) {
                                return ['title' => $val];
                            }, ArrayHelper::map($skills, 'id', 'full_name'))
                ]
            ])
            ?>
        </div>
        <div class="column">
            <?= $form->field($model, 'email', ['template' => $template])->textInput(['class' => 'fieldin'])->label('Email *') ?>
            <?=
            $form->field($model, 'country_code', ['template' => $template])->widget(Select2::class, [
                'data' => $countries,
                'pluginEvents' => [
                    'select2:select' => 'function() {
                    let itis = window.intlTelInputGlobals.instances;
                    for (let iti in itis) {
                        itis[iti].setCountry(this.value);
                    }
                 }',
                ]
            ])
            ?>
            <?=
            $form->field($model, 'region_code', ['template' => $template])->widget(DepDrop::class, [
                'data' => $regions,
                'type' => DepDrop::TYPE_SELECT2,
                'pluginOptions' => [
                    'placeholder' => 'Выберите регион',
                    'depends' => ['registrationform-country_code'],
                    'url' => Url::to(['region/dep-drop']),
                ],
            ])
            ?>
            <?=
            $form->field($model, 'phone', ['template' => $template])->widget(PhoneInput::class, [
                'jsOptions' => [
                    'preferredCountries' => ['ru', 'kz', 'by', 'md', 'az', 'tm', 'tj', 'ua']
                ],
                'options' => [
                    'placeholder' => ' ',
                    'class' => 'fieldin',
                    'autocomplete' => 'new-password'
                ]
            ])
            ?>
            <?= $form->field($model, 'password', ['template' => $template])->passwordInput(['class' => 'fieldin'])->label($model->getAttributeLabel('password') . ' *') ?>
            <?= $form->field($model, 'password_confirm', ['template' => $template])->passwordInput(['class' => 'fieldin'])->label($model->getAttributeLabel('password_confirm') . ' *') ?>
        </div>
        <div class="agreement">
            Поля, отмеченные звездочкой (*), обязательны для заполнения.
            <?= $form->field($model, 'agreement_personal')->checkbox(['template' => '{input}{label}<b>Согласен с ' . $agreement . ' и ' . $policy . '</b>{error}'])->label('', ['class' => 'checkbox-label']); ?>
        </div>
        <div class="column">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'pirs-btn-blue square pirs-btn-register', 'name' => 'registration-button']) ?>
        </div>
        <div class="column">
            <?= Html::a('У меня уже есть аккаунт, войти под ним', ['/site/login'], ['class' => 'square pirs-btn-to-login']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
