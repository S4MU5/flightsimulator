<?php

namespace app\modules\profile\controllers;

use app\modules\profile\models\UserNewPassword;
use app\modules\profile\models\UserSaveChanges;
use yii\web\Controller;

/**
 * Default controller for the `Profile` module
 */
class ProfileController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new UserNewPassword();
        $model->id = \Yii::$app->user->id;
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionSave(){
        $model = new UserSaveChanges();
        $model->load($this->request->post()['User']);
        $model->first_name = $this->request->post()['User']['first_name'];
        $model->id = $this->request->post()['User']['id'];
        $model->patronymic = $this->request->post()['User']['patronymic'];
        $model->last_name = $this->request->post()['User']['last_name'];
        if ($model->validate()){

            $model->changeUserSet();

            return $this->redirect(['index']);
        } else {
            return $this->asJson([
                'success' => false,
                'errors' => $model->errors
            ]);
        }
    }

    public function actionPass(){

    }
}
