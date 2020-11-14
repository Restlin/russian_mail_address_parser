<?php

use yii\web\View;
use app\models\RowSearch;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $searchModel RowSearch */
/* @var $dataProvider ActiveDataProvider */

?>

<h3>Количество строк: <?= $dataProvider->totalCount?></h3>

<h3>Среднее время обработки файла: <?= $searchModel->getAvgSpeed() ?> сек.</h3>
