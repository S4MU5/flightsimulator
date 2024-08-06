<?php

use app\models\Borts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\modules\admin\models\IssueSaveForm;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$formModel = new IssueSaveForm();

$this->title = 'Вопросы к '.$bortModel->getAttribute('name');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="col-xl-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <div class="panel-toolbar ml-auto">
                    <a href="<?=Url::to(['/admin/borts'])?>" class="btn btn-sm btn-primary mr-2">Назад</a>
                    <a href="<?= Url::to([ '/admin/borts/questions' ]).'?id='.$bortModel->getAttribute('id') ?>"
                       class="btn btn-sm btn-primary mr-2"
                       data-pjax="0"
                       title="Сбросить фильтры">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                    <?
                    Modal::begin([
                        'id'          => 'modal_new_issue',
                        'title'       => Html::tag('h4', 'Добавление вопроса', [ 'class' => 'modal-title' ]),
                        'closeButton' => ['class' => 'btn btn-sm close fa-solid fa-xmark' ],
                        'toggleButton' => [
                            'class' => 'btn btn-sm btn-primary x-create',
                            'label' => 'Добавить'
                        ],
                        'footer'      => Html::tag('button', 'Сохранить', [
                            'class' => 'btn btn-primary',
                            'type'  => 'submit',
                            'form'  => 'form_new_issue' ]),
                        'options'     => [ 'tabindex' => '' ],
                    ]);
                    $form = ActiveForm::begin([
                        'id'                     => 'form_new_issue',
                        'action'                 => Url::to([ '/admin/borts/issuesave' ]),
                        'enableAjaxValidation'   => true,
                        'enableClientValidation' => false,
                        'enableClientScript'     => true,
                    ]);
                    echo $form->field($formModel, "id", [
                        'template' => '{input}',
                        'options' => ['class' => ''],
                    ])->hiddenInput();

                    echo $form->field($formModel, "bort_id", [
                        'template' => '{input}',
                        'options' => ['class' => ''],
                    ])->hiddenInput();
                    ?>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <?= $form->field($formModel, 'issue')->textInput([ 'class' => 'form-control' ]) ?>
                            </div>
                            <div class="form-group">
                                <?
                                echo MultipleInput::widget([
                                    'model' => $formModel,
                                    'attribute' => 'answer',
                                    'columns' => [
                                        [
                                            'name' => 'answer',
                                            'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                                        ],
                                        [
                                            'name' => 'right',
                                            'type' => MultipleInputColumn::TYPE_CHECKBOX,
                                        ]
                                    ]
                                ]);
                                ?>
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
                            'attribute' => 'issue',
                            'format' => 'text',
                            'label' => 'Вопрос'
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{edit}',
                            'buttons' => [
                                'edit' => fn($url, $model) => Html::a(
                                    '<i class="fa fa-pencil"></i>',
                                    $url,
                                    [
                                        'class' => 'btn btn-xs btn-light btn-icon x-edit',
                                        'data-json' => Json::encode($model),
                                    ]
                                ),
                            ]
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{activatequest} {deletequest}',
                            'buttons' => [
                                'activatequest' => function($url, $model, $key){
                                    return Html::a('Деактивировать',$url, ['class' => 'btn btn-primary']);
                                },
                                'deletequest' => function($url, $model, $key){
                                    return Html::a('Удалить',$url, ['class' => 'btn btn-primary']);
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
<script>
    if (buttons = document.querySelector('.x-edit')){
        document.querySelector('.x-edit').addEventListener('click', function (e) {
            document.querySelector('#issuesaveform-bort_id').value = <?=$bortModel->getAttribute('id')?>;
        });
    }

    document.querySelector('.x-create').addEventListener('click', function (e) {
        document.querySelector('#issuesaveform-bort_id').value = <?=$bortModel->getAttribute('id')?>;
    });
</script>
<?php
$this->registerJs('
$(document).ready(function (){
    let $form = $("#form_new_issue"),
    $modal = $("#modal_new_issue");
    $("body").on("click", ".x-create", function (e) {
        $form[0].reset();
        var int = document.getElementsByClassName("js-input-remove");
        var integer = int.length;
        for (var i = 0; i < integer; i++){
            int[i].click();
        }
    });
    
    $("body").on("click", ".x-edit", function (e) {
        e.preventDefault();
        $(".x-create").click();
        let formData = $(this).data("json");
        for (let attr in formData) {
            var int = document.getElementsByClassName("js-input-remove");
            var integer = int.length;
            for (var i = 0; i < integer; i++){
                int[i].click();
            }

            if(attr == "answer"){
                let answer = JSON.parse(formData[attr]);
                var element = document.getElementsByClassName("multiple-input-list__btn")[0];
                for (let i = 0; i < answer.length-1; i++){
                    element.click();
                }
                console.log(answer);
                for (let i = 0; i < answer.length; i++){
                    document.getElementsByName("issuesaveform[answer]["+i+"][answer]")[0].value = answer[i].answer;
                    if (answer[i].right == 1) document.getElementsByName("issuesaveform[answer]["+i+"][right]")[1].click();
                }
            } else {
                $form.find("#' . $formModel->formName() . '-" + attr).val(formData[attr]);
            }
        }
        $modal.modal();
        
        return false;
    });
});
');
?>