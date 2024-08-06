<?php
use yii\grid\GridView;
use yii\bootstrap5\Tabs;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="admin-default-index">
    <?
    echo Tabs::widget([
       'items' => [
           [
               'label' => 'Открытые',
               'content' => GridView::widget([
                   'dataProvider' => $dataProvider1,
                   'columns' => [
                       [
                           'attribute' => 'bort.name',
                           'format' => 'text',
                           'label' => 'Название'
                       ],
                       [
                           'attribute' => 'bort.number',
                           'format' => 'text',
                           'label' => 'Гос номер'
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
                       ],
                       [
                           'attribute' => 'status',
                           'format'    => 'raw',
                           'value'     => function($model){
                               switch ($model->status){
                                   case 1:
                                       return 'В обработке';
                                       break;
                                   case 2:
                                       return 'Отклонена';
                                       break;
                                   case 3:
                                       return 'Одобрена';
                                       break;
                               }
                           },
                           'label' => 'Статус',
                       ]
                   ]
               ])
           ],
           [
               'label' => 'История',
               'content' => GridView::widget([
                   'dataProvider' => $dataProvider2,
                   'columns' => [
                       [
                           'attribute' => 'bort.name',
                           'format' => 'text',
                           'label' => 'Название'
                       ],
                       [
                           'attribute' => 'bort.number',
                           'format' => 'text',
                           'label' => 'Гос номер'
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
                       ],
                       [
                           'attribute' => 'comments',
                           'format' => 'text',
                           'label' => 'Комментарий'
                       ],
                       [
                           'attribute' => 'status',
                           'format'    => 'raw',
                           'value'     => function($model){
                               switch ($model->status){
                                   case 1:
                                       return 'В обработке';
                                       break;
                                   case 2:
                                       return 'Отклонена';
                                       break;
                                   case 3:
                                       return 'Одобрена';
                                       break;
                               }
                           },
                           'label' => 'Статус',
                       ]
                   ]
               ])
           ],
           [
               'label' => 'Статистика',
               'content' => 'Раздел допиливается'
           ]
       ]
    ]);
    ?>
</div>