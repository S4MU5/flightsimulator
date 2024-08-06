<?php

namespace app\controllers;

use app\models\BookingSaveForm;
use app\models\BookingSearch;
use app\models\Borts;
use app\models\BortsSearch;
use app\models\Issues;
use app\models\User;
use yii\bootstrap5\ActiveForm;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use Yii;

/**
 * BookingController implements the CRUD actions for Borts model.
 */
class BookingController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['canPilot'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $ban = User::isBanned(Yii::$app->user->id);
        if ($ban != null && $ban > date('Y-m-d H:i:s')):
            return $this->render('banned', [
                'ban' => $ban
            ]);
        endif;
        $searchModel = new BortsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $ban = User::isBanned(Yii::$app->user->id);
        if ($ban != null && $ban > date('Y-m-d H:i:s')):
            return $this->render('banned', [
                'ban' => $ban
            ]);
        endif;
        $data = Issues::find()
            ->where(['bort_id' => $id])
            ->orderBy(new Expression('rand()'))
            ->asArray()
            ->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'issue' => $data
        ]);
    }

    public function actionCheck(){
        if (Yii::$app->request->isAjax){
            $date = Yii::$app->request->post()['datepicker'];
            $id = Yii::$app->request->post()['id'];
            $searchModel = new BookingSearch();
            $dataProvider = $searchModel->search($this->request->queryParams, $id, $date);
            return $this->renderAjax('check', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'bort_id' => $id
            ]);
        }
    }

    public function actionSave() {
        $model = new BookingSaveForm();
        $model->load($this->request->post());
        if ($this->request->isAjax){
            return $this->asJson(
                ActiveForm::validate($model)
            );
        }

        if ($model->validate()){
            $model->save();

            return $this->redirect(['index']);
        } else {
            return $this->asJson([
                'success' => false,
                'errors' => $model->errors
            ]);
        }
    }

    public function actionAnswer(){
        if ($this->request->isAjax){
            if ($this->request->post()['answer'] == 1){
                $this->asJson([
                    'success' => true,
                    'error' => "nothing wrong it's ok bro"
                ]);
            } else {
                User::banUser(Yii::$app->user->id);
                $this->redirect(Url::to('index'));
            }
        } else {
            return $this->asJson([
               'success' => false,
               'error' => 'wrong request params (not ajax)'
            ]);
        }
    }


    protected function findModel($id)
    {
        if (($model = Borts::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
