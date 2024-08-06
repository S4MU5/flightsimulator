<?php

use app\models\Borts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use app\modules\admin\models\BortsSaveForm;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$formModel = new BortsSaveForm();

$this->title = 'Борты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="col-xl-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="panel-toolbar ml-auto">
                    <a href="<?= Url::to(['/admin'])?>" class="btn btn-sm btn-primary mr-2">Назад</a>
                    <a href="<?= Url::to([ '/admin/borts' ]); ?>"
                       class="btn btn-sm btn-primary mr-2"
                       data-pjax="0"
                       title="Сбросить фильтры">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                    <?
                    Modal::begin([
                        'id'          => 'modal_new_bort',
                        'title'       => Html::tag('h4', 'Добавление борта', [ 'class' => 'modal-title' ]),
                        'closeButton' => ['class' => 'btn btn-sm close fa-solid fa-xmark' ],
                        'toggleButton' => [
                            'class' => 'btn btn-sm btn-primary',
                            'label' => 'Добавить'
                        ],
                        'footer'      => Html::tag('button', 'Сохранить', [
                            'class' => 'btn btn-primary',
                            'type'  => 'submit',
                            'form'  => 'form_new_bort' ]),
                        'options'     => [ 'tabindex' => '' ],
                    ]);
                    $form = ActiveForm::begin([
                        'id'                     => 'form_new_bort',
                        'action'                 => Url::to([ '/admin/borts/save' ]),
                        'enableAjaxValidation'   => true,
                        'enableClientValidation' => false,
                        'enableClientScript'     => true,
                    ]);
                    ?>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <?= $form->field($formModel, 'name')->textInput([ 'class' => 'form-control' ]) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($formModel, 'number')->textInput(['class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <?
                    ActiveForm::end();
                    Modal::end();
                    ?>
                </div>
            </div>
            <div class="borts-index">


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
                            'template' => '{teh} {view} {journal} {questions}',
                            'buttons' => [
                                'teh' => function($url, $model, $key){
                                    return Html::a('Проведение Т.О.', $url, ['class' => 'btn btn-primary']);
                                },
                                'journal' => function($url, $model, $key){
                                    return Html::a('Бортовой журнал',$url, ['class' => 'btn btn-primary']);
                                },
                                'questions' => function($url, $model, $key){
                                    return Html::a('Вопросы',$url, ['class' => 'btn btn-primary']);
                                },
                                'view' => function($url, $model, $key){
                                    return Html::a('Выгрузка',$url, ['class' => 'btn btn-primary']);
                                },
                            ]
                        ]
                    ]
                ])
                ?>
            </div>
        </div>
    </div>
</div>