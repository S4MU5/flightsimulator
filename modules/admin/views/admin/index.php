<?
use yii\grid\GridView;
use yii\bootstrap5\Tabs;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\BookingSaveForm;
use yii\helpers\Json;

$formModel = new BookingSaveForm();
?>
<?

Modal::begin([
    'id'          => 'modal_decline',
    'title'       => Html::tag('h4', 'Отмена брони', [ 'class' => 'modal-title' ]),
    'closeButton' => ['class' => 'btn btn-sm close fa-solid fa-xmark' ],
    'toggleButton' => [
        'class' => 'btn btn-sm btn-primary x-decline_x',
        'label' => 'Добавить'
    ],
    'footer'      => Html::tag('button', 'Сохранить', [
        'class' => 'btn btn-primary',
        'type'  => 'submit',
        'form'  => 'form_decline' ]),
    'options'     => [ 'tabindex' => '' ]
]);
$form = ActiveForm::begin([
    'id'                     => 'form_decline',
    'action'                 => Url::to([ '/admin/admin/decline' ]),
    'enableAjaxValidation'   => true,
    'enableClientValidation' => false,
    'enableClientScript'     => true,
]);
echo $form->field($formModel, "id", [
    'template' => '{input}',
    'options' => ['class' => ''],
])->hiddenInput();
?>
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="form-group">
            <?= $form->field($formModel, 'comments')->textInput([ 'class' => 'form-control' ]) ?>
        </div>
    </div>
</div>
<?
ActiveForm::end();
Modal::end();
?>
<div class="admin-default-index">
    <?
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Активные заявки',
                'content' => GridView::widget([
                    'dataProvider' => $dataProvider,
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
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{accept} {decline}',
                            'buttons' => [
                                'decline' => function($url, $model, $key){
                                    return Html::a('Отклонить', $url, [
                                        'class' => 'btn btn-danger x-decline',
                                        'data-json' => Json::encode($model)
                                    ]);
                                },
                                'accept' => function($url, $model, $key) {
                                    return Html::a('Одобрить', $url, ['class' => 'btn btn-primary']);

                                }
                            ]
                        ]
                    ]
                ]),
                'active' => true
            ],
            [
                'label' => 'Принятые заявки',
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
                        ],
//                        [
//                            'class' => ActionColumn::class,
//                            'template' => '{retry}',
//                            'buttons' => [
//                                'retry' => function($url, $model, $key){
//                                    return Html::a('Изменить', $url, ['class' => 'btn btn-primary']);
//                                }
//                            ]
//                        ]
                    ]
                ]),
            ],
            [
                'label' => 'Аккаунты',
                'items' => [
                    [
                        'label' => 'Права доступа',
                        'content' => 'DropdownA, Anim pariatur cliche...',
                    ],
                    [
                        'label' => 'Бан, справка, оплата',
                        'content' => 'DropdownB, Anim pariatur cliche...',
                    ],
                    [
                        'label' => 'Редактирование профиля',
                        'url' => 'http://www.example.com',
                    ],
                ],
            ],
            [
                'label' => 'Настройка бортов',
                'url' => '/admin/borts',
            ],
        ],
    ]);
    ?>
</div>
<?
$this->registerJs('
$(document).ready(function (){
    let $form = $("#form_decline"),
    $modal = $("#modal_decline");
    
    $("body").on("click", ".x-decline", function (e) {
        e.preventDefault();
        $form[0].reset();
        $(".x-decline_x").click();
        let formData = $(this).data("json");
        for (let attr in formData) {
            $form.find("#' . $formModel->formName() . '-" + attr).val(formData[attr]);
        }
        $modal.modal();
        
        return false;
    });
});
');
?>