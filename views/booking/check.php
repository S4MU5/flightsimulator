<?
use yii\grid\GridView;
echo '<div class="gridview_inner">';
echo '<p>Полеты, запланированные на этот день и позже</p>';
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'start_date',
            'format' => 'text',
            'label' => 'Вылет'
        ],
        [
            'attribute' => 'end_date',
            'format' => 'text',
            'label' => 'Прилет'
        ]
    ]
]);
echo \yii\helpers\Html::a('Забронировать на другую дату',\yii\helpers\Url::to(['/booking/view?id='.$bort_id]), []);
echo '</div>';
?>