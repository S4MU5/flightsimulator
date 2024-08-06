<?
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
$formModel = \app\models\User::findOne(Yii::$app->user->id);
?>
<div class="">
    <div class="block_naming" style="display: flex; flex-direction: row">
        <div class="fa-image-portrait portrait avatar">
        </div>
        <?
        $form = ActiveForm::begin([
            'id'                     => 'form_change_user',
            'action'                 => Url::to([ '/profile/profile/save' ]),
            'enableAjaxValidation'   => true,
            'enableClientValidation' => false,
            'enableClientScript'     => true,
        ]);
        ?>
        <div class="row mt-2">
            <div class="col-lg-12">
                <?=$form->field($formModel, "id", [
                    'template' => '{input}',
                    'options' => ['class' => ''],
                ])->hiddenInput()?>
                <div class="form-group">
                    <?= $form->field($formModel, 'first_name')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($formModel, 'last_name')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($formModel, 'patronymic')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
            </div>
        </div>
        <?
        echo Html::tag('button', 'Сохранить', [
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'form'  => 'form_change_user' ]);

        ActiveForm::end();
        ?>
    </div>
    <div class="password_changer">
        <h3>Замена пароля</h3>
        <?
        $form2 = ActiveForm::begin([
            'id'                     => 'form_change_pass',
            'action'                 => Url::to([ '/profile/profile/pass' ]),
            'enableAjaxValidation'   => true,
            'enableClientValidation' => false,
            'enableClientScript'     => true,
        ]);
        ?>
        <div class="row mt-2">
            <div class="col-lg-12">
                <?=$form2->field($model, "id", [
                    'template' => '{input}',
                    'options' => ['class' => ''],
                ])->hiddenInput()?>
                <div class="form-group">
                    <?= $form2->field($model, 'old_password')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
                <div class="form-group">
                    <?= $form2->field($model, 'password')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
                <div class="form-group">
                    <?= $form2->field($model, 'password_twice')->textInput([ 'class' => 'form-control' ]) ?>
                </div>
            </div>
        </div>
        <?
        echo Html::tag('button', 'Сохранить', [
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'form'  => 'form_change_user' ]);

        ActiveForm::end();
        ?>
    </div>
    <div class="request_pilot">
        <h3>Отправка запроса на доступ к бронированию самолетов</h3>
        <?echo !Yii::$app->user->can('canPilot') ? '<button class="btn btn-primary">Отправить заявку</button>' : '<h4>У вас уже есть доступ к бронированию</h4>
        <p>Если доступ так и не появился, проверьте, есть ли у вас временная блокировка. При отсутствии таковой, обратитесь к администратору сайта</p>';?>
    </div>
</div>