<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\models\Borts $model */
/** @var ActiveForm $form */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Borts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$formModel = new \app\models\BookingSaveForm();
$formModel->bort_id = $model->getAttribute('id');
$formModel->user_id = Yii::$app->user->id;
?>
<div class="container">
    <div class="borts-view">

        <h1><?= Html::encode($this->title) ?></h1>
        <h3>Внимание, после нажатия кнопки "Забронировать" будет контрольный вопрос с таймером</h3>
        <div id="data_picker" class="well border border-secondary rounded p-1 booking-view date_checker" style="width: fit-content">
            <?
            echo DatePicker::widget([
                'name' => 'dater',
                'type' => DatePicker::TYPE_INLINE,
                'value' => date('Y-m-d'),
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'multidate' => false
                ]
            ]);
            ?>
            <br>
            <button class="btn btn-success" onclick="datepick()" style="margin: auto 0">Забронировать с этого дня</button>
        </div>
        <div id="gridview-controller"></div>
        <div class="booking-view" id="order_viewer" style="display: none">
            <br>

            <?php $form = ActiveForm::begin([
                'action' => Url::to(['/booking/save']),
                'id' => 'form_new_booking',
                'enableAjaxValidation'   => true,
                'enableClientValidation' => false,
                'enableClientScript'     => true
            ]);

            echo $form->field($formModel, 'bort_id')->hiddenInput(['value' => $model->getAttribute('id')])->label(false);

            echo $form->field($formModel, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false);

            echo $form->field($formModel, 'status')->hiddenInput(['value' => 1])->label(false);

            echo $form->field($formModel, 'start_date_first')->hiddenInput()->label(false);
            ?>

            <?= $form->field($formModel, 'start_date')->widget(TimePicker::className(), [
                'pluginOptions' => [
                    'format' => 'H:ii:ss',
                    'minuteStep' => 5,
                    'showSeconds' => false,
                    'showMeridian' => false,
                ]
            ]) ?>
            <?= $form->field($formModel, 'end_date')->widget(DateTimePicker::className(), [
                'options' => ['placeholder' => 'Введите дату'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd H:ii:ss'
                ]
            ]) ?>
            <?= $form->field($formModel, 'purpose') ?>

            <?
            Modal::begin([
                'id' => 'modal_questions',
                'title' => Html::tag('h4', 'Контрольный вопрос!', ['class' => 'modal-title']),
                'closeButton' => [
                    'class' => 'btn btn-sm close fa-solid fa-xmark',
                    'style' => 'display: none;',
                    'id' => 'modal_close'
                ],
                'toggleButton' => [
                    'class' => 'btn btn-sm btn-primary',
                    'label' => 'Контрольный вопрос',
                    'id' => 'modal_openner'
                ],
                'footer' => Html::tag('button', 'Ответить', [
                    'class' => 'btn btn-primary',
                    'id' => 'answer_question',
                    'form' => 'form_questions']),
                    'options' => ['tabindex' => ''],
            ]);
            ?>
            <div class="row mt-2">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div id="timer"></div>
                    </div>
                    <form>
                        <div class="form-group">
                            <h5><?=$issue['issue']?></h5>
                        </div>
                        <?
                        $answers = json_decode($issue['answer'], 1);
                        foreach ($answers as $key => $answer):
                            ?>
                            <div class="form-chech">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios<?=$key?>" value="<?=$answer['right']?>">
                                <label class="form-check-label" for="exampleRadios<?=$key?>"><?=$answer['answer']?></label>
                            </div>
                        <? endforeach; ?>
                    </form>
                </div>
            </div>
            <?
            Modal::end();
            ?>

            <div class="form-group" style="display: none;" id="submit_btn">
                <?= Html::tag('button', 'Забронировать', [
                    'class' => 'btn btn-primary',
                    'type' => 'submit',
                    'form' => 'form_new_booking'
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div><!-- booking-view -->

    </div>
</div>
<script>
    let datepicker = document.getElementsByName('dater')[0];

    function datepick() {
        $.ajax({
            url: '/booking/check',
            method: 'post',
            data: {datepicker: datepicker.value + ' 00:00:00', id: <?=$model->getAttribute('id')?>},
            success: function (data) {
                let inner = document.querySelector('.gridview_inner');
                if (inner != null) inner.remove();
                document.getElementById('gridview-controller').insertAdjacentHTML('afterbegin', data);
                document.querySelector('#data_picker').style.display = 'none';
                document.querySelector('#order_viewer').style.display = 'block';
                document.querySelector('#bookingsaveform-start_date_first').value = datepicker.value;
            }
        });
    }
    var timeLeft = 30;
    var elem = document.querySelector('#timer');
    var timerId;
    let modalka = document.querySelector('#modal_openner');
    let modal = document.querySelector('#modal_questions');
    let accept = document.querySelector('#answer_question');

    accept.addEventListener('click', function (e){
       clearTimeout(timerId);
       validateAjaxBan();
    });

    modalka.addEventListener('click', function (e){
       if (timeLeft == 30){
           timerId = setInterval(countdown, 1000);
       }
    });

    function countdown() {
        if (timeLeft == -1){
            clearTimeout(timerId);
            ajaxBan();
        } else {
            elem.innerHTML = timeLeft + ' ты ща помрешь, отвечай быстрее';
            timeLeft--;
        }
    }

    function ajaxBan() {
        $.ajax({
            url: '/booking/answer',
            method: 'post',
            data: {answer: 0}
        });
    }

    function validateAjaxBan(){
        let answer, inpts = modal.querySelectorAll('input');
        inpts.forEach((inp) => {
            if (inp.checked){
                answer = inp.value;
            }
        });
        $.ajax({
            url: '/booking/answer',
            method: 'post',
            data: {answer: answer},
            success: function (data){
                if (data.success == true){
                    modal.querySelector('#modal_close').click();
                    modalka.style.display = 'none';
                    document.querySelector('#submit_btn').style.display = 'block';
                }
            }
        });
    }
</script>