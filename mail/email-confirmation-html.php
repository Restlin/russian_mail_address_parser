<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
Здравствуйте, <?= Html::encode($userName); ?> !<br />
<br />
Чтобы завершить регистрацию, пожалуйста, введите код подтверждения.<br />
<br />
<?= Html::encode($code); ?>
<br />
Ссылка для ввода кода:
<?= $url ?>