<?php

use yii\web\View;
use app\models\RowSearch;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $searchModel RowSearch */
/* @var $dataProvider ActiveDataProvider */

?>

<h3>Количество строк: <?= $dataProvider->totalCount?></h3>

<h3>Скорость обработки: <?= $searchModel->getAvgSpeed() ?> строк/секунда</h3>
