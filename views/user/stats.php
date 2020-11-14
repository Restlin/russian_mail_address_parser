<?php

use yii\web\View;
use app\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

?>

<h3>Количество пользователей: <?= $dataProvider->totalCount ?></h3>
