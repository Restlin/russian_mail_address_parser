<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\user\models\User */
/* @var $resetURL string */
?>

Здравствуйте, <?= Html::encode($userName) ?>.<br />
<br />
Если это было ошибкой, просто проигнорируйте это письмо, и ничего не произойдёт.<br />
<br />
Чтобы сбросить пароль, нажмите ссылку сброса ниже:<br />
<?= $resetURL ?><br />
<br />