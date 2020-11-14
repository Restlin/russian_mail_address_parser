<?php
/* @var $this View */

use yii\helpers\Html;
use yii\web\View;

$this->title = 'Вы успешно зарегистрировались!';
?>
<div class="outer-conteiner" style="min-height: 500px; display: flex; flex-direction: column; justify-content: center;">
    <div class="inner-conteiner site-reset-pwd">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Ваша регистрация прошла успешно!</p>
        <div class="site-index-control">
            <?= Html::a('Войти', ['/site/login'], ['class' => 'pirs-btn pirs-orange']) ?>
        </div>
    </div>
</div>
