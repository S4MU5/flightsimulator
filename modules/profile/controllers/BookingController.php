<?php

namespace app\modules\profile\controllers;

use app\models\BookingSearch;

class BookingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider1 = $searchModel->searchForUser($this->request->queryParams, 1, \Yii::$app->user->id);
        $dataProvider2 = $searchModel->searchForUser($this->request->queryParams, 2, \Yii::$app->user->id);
        $dataProvider3 = $searchModel->searchForUser($this->request->queryParams, 3, \Yii::$app->user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3
        ]);
    }

}
