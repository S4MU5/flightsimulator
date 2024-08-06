<?php

use app\models\Borts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BortsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Аппараты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="borts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
    $form = ActiveForm::begin([
        'action' => Url::to(['admin/borts/save']),
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
        'enableClientScript'     => true
    ]);
    ?>
    <? ActiveForm::end()?>
</div>
