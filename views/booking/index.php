<?php

use app\models\Borts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\User;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Бронирование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="borts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'text',
                    'label' => 'Название'
                ],
                [
                    'attribute' => 'number',
                    'format' => 'text',
                    'label' => 'Гос номер'
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view}',
                    'buttons' => [
                            'view' => function($url, $model, $key){
                                return Html::a('Выбрать',$url, ['class' => 'btn btn-primary']);
                            }
                    ]
                ]
        ]
    ])
    ?>
</div>
