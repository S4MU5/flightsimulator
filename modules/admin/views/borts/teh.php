<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\ActiveForm;
use app\modules\admin\models\ExploitSaveForm;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Техническое обслуживание';
$this->params['breadcrumbs'][] = $this->title;

$formModel = new ExploitSaveForm();
?>
<div class="borts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?
    $form = ActiveForm::begin([
        'id'                     => 'form_new_exploit',
        'action'                 => Url::to([ '/admin/borts/newteh' ]),
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
        'enableClientScript'     => true,
    ]);
    $last = !$lastExploit || $lastExploit->getAttribute('status') == 1 ? 0 : 1;
    ?>
    <?=$form->field($formModel, 'last', ['template' => '{input}', 'options' => ['class' => '']])->hiddenInput(['value' => $last])?>
    <?=$form->field($formModel, 'bort_id', ['template' => '{input}', 'options' => ['class' => '']])->hiddenInput(['value' => $bort_id])?>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="form-group">
                <?= $form->field($formModel, 'comment')->textInput([ 'class' => 'form-control' ]) ?>
            </div>
            <div class="form-group">
                <?=  Html::tag('button', 'Отправить', [
                    'class' => 'btn btn-primary',
                    'type'  => 'submit',
                    'form'  => 'form_new_exploit' ])?>
            </div>
        </div>
    </div>
    <?
    ActiveForm::end();
    ?>
    <? if (!$lastExploit || $lastExploit->getAttribute('status') == 1):?>
        <div>
            <p>Этот борт находится в эксплуатации, чтобы приостановить бронирование введите причину (если это техническое обслуживание) и нажмите кнопку отправить</p>
        </div>
    <?else:?>
        <div>
            <p>Этот борт находится на техническом обслуживании или остановлен в бронировании, чтобы вернуть в эксплуатацию борт, введите причину (если это техническое обслуживание) и нажмите кнопку отправить</p>
        </div>
    <?endif;?>
    <?
    echo GridView::widget([
       'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value'     => function($model){
                    switch ($model->status){
                        case 0:
                            return 'Вышел из эксплуатации';
                            break;
                        case 1:
                            return 'Вернулся в эксплуатацию';
                            break;
                    }
                },
                'label' => 'Статус Т.О.'
            ],
            [
                'attribute' => 'date',
                'format' => 'text',
                'label' => 'Дата'
            ],
            [
                'attribute' => 'comment',
                'format' => 'text',
                'label' => 'Комментарий'
            ]
        ]
    ]);
    ?>

