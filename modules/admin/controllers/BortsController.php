<?php

namespace app\modules\admin\controllers;

use app\models\BookingSearch;
use app\models\Borts;
use app\models\BortsSearch;
use app\models\ExploitSearch;
use app\models\Issues;
use app\modules\admin\models\BortsSaveForm;
use app\modules\admin\models\ExploitSaveForm;
use app\modules\admin\models\IssueSaveForm;
use app\modules\admin\models\IssueSearch;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

class BortsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new BortsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionQuestions($id)
    {
        $searchModel = new IssueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('questions', [
            'model' => $this->findIssues($id),
            'bortModel' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionJournal($id)
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->searchForBort($this->request->queryParams, $id);
        return $this->render('journal', [
//            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionTeh($id){

        $bort = Borts::findOne($id);
        $last = $bort->getLastExploit();

        $searchModel = new ExploitSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('teh', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lastExploit' => $last,
            'bort_id' => $id
        ]);
    }

    public function actionNewteh(){

        $model = new ExploitSaveForm();
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

    protected function findModel($id)
    {
        if (($model = Borts::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findIssues($id){
        if ($model = Issues::findAll(['bort_id' => $id]) !== null){
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSave(){
        $model = new BortsSaveForm();
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
                'success'=> false,
                'errors' => $model->errors
            ]);
        }
    }

    public function actionIssuesave(){
        $model = new IssueSaveForm();
        $model->load($this->request->post());
        $model->answer = $this->request->post('issuesaveform', [])['answer'];
        $model->bort_id = $this->request->post('issuesaveform', [])['bort_id'];
        $model->id = $this->request->post('issuesaveform', [])['id'];

        if ($this->request->isAjax) {
            return $this->asJson(
                ActiveForm::validate($model)
            );
        }

        if ($model->validate()) {
            $model->save();

            return $this->redirect([ 'questions?id='.$model->bort_id ]);
        } else {
            return $this->asJson([
                'success' => false,
                'errors'  => $model->errors,
            ]);
        }
    }

    public function actionActivatequest($id){
        $issue = Issues::findOne($id);

        if ($issue){
            $activate = $issue->getAttribute('active');
            $activate == 1 ? $issue->setAttribute('active', 0) : $issue->setAttribute('active', 1);
            if (!$issue->save()){
                throw new BadRequestHttpException("Ошибка сохранения элемента");
            } else {
                return $this->redirect('/admin/borts');
            }
        } else {
            return 'Issue '.$id.' not found';
        }
    }

    public function actionDeletequest($id){
        $issue = Issues::findOne($id);

        if ($issue){
            if (!$issue->delete()){
                throw new BadRequestHttpException("Ошибка удаления элемента");
            } else {
                return $this->redirect('/admin/borts');
            }
        } else {
            return 'Issue '.$id.' not found';
        }
    }

}
