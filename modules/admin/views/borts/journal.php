<?php

use app\models\Borts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Бортовой журнал';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="borts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'user.first_name',
                'format' => 'text',
                'label' => 'Имя'
            ],
            [
                'attribute' => 'user.last_name',
                'format' => 'text',
                'label' => 'Фамилия'
            ],
            [
                'attribute' => 'start_date',
                'format' => 'text',
                'label' => 'Вылет'
            ],
            [
                'attribute' => 'end_date',
                'format' => 'text',
                'label' => 'Прилет'
            ],
            [
                'attribute' => 'purpose',
                'format' => 'text',
                'label' => 'Цель'
            ]
        ]
    ])
    ?>
</div>
