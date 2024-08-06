<?php

namespace app\modules\admin\controllers;

use app\models\Bookings;
use app\models\BookingSearch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class AdminController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->searching($this->request->queryParams, 1);
        $dataProvider2 = $searchModel->searchStatus($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2
        ]);
    }

    public function actionAccept($id){
        $booking = Bookings::findOne($id);
        if ($booking){
            $booking->setAttribute('status', 3);
            $start = $booking->getAttribute('start_date');
            $end = $booking->getAttribute('end_date');
            $anotherBookings = Bookings::find()
                ->where(['status' => 1])
                ->andWhere(['bort_id' => $booking->getAttribute('bort_id')])
                ->andWhere([
                    'and',
                    ['>', 'start_date', $start],
                    ['<', 'start_date', $end]
                ])
                ->orWhere([
                    'and',
                    ['>', 'end_date', $start],
                    ['<', 'end_date', $end]
                ])
                ->all();
            foreach ($anotherBookings as $anotherBooking){
                $this->declineMod($anotherBooking->getAttribute('id'), 'Ваша бронь снята по причине пересечения с только что утвержденным полетом');
            }
            if (!$booking->save()){
                throw new BadRequestHttpException("Ошибка сохранения элемента");
            } else {
                return $this->redirect('/admin');
            }
        } else {
            return 'Booking '.$id.' not found';
        }
    }

    public function actionDecline(){
        if ($this->request->isAjax) {
        }
        $id = $this->request->post('bookingsaveform', [])['id'];
        $comments = $this->request->post('bookingsaveform', [])['comments'];
        if ($id){
            if (!$this->declineMod($id, $comments)){
                return 'Ошибка отмены брони '.$id;
            } else {
                return $this->redirect('/admin');
            }
        } else {
            return 'Не задано обязательное поле id';
        }
    }

    private function declineMod($id, $comments = ''){
        $booking = Bookings::findOne($id);
        if ($booking){
            $booking->setAttribute('comments', $comments);
            $booking->setAttribute('status', 2);
            if (!$booking->save()){
                throw new BadRequestHttpException("Ошибка сохранения элемента");
            }
            return true;
        } else {
            return false;
        }
    }
}
