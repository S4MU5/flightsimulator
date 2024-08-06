<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BortsSearch represents the model behind the search form of `app\models\Borts`.
 */
class BookingSearch extends Bookings
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bort_id', 'user_id', 'status', 'start_date', 'end_date', 'purpose'], 'required'],
            [['bort_id', 'user_id', 'status'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['comments', 'purpose'], 'string', 'max' => 255],
            [['bort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Borts::class, 'targetAttribute' => ['bort_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function searchStatus(array $params){
        $query = Bookings::find()
            ->joinWith('user', true, 'INNER JOIN')
            ->joinWith('bort', true, 'INNER JOIN');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andWhere([
            '>', 'start_date', date('Y-m-d H:i:s'),
        ]);
        $query->andWhere([
            'status' => ['2', '3']
        ]);

        return $dataProvider;
    }

    public function searchForBort($params, $bort_id){
        $query = Bookings::find()
            ->joinWith('user', true, 'INNER JOIN')
            ->joinWith('bort', true, 'INNER JOIN');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
            'bort_id'  => $bort_id
        ]);

        $query->andFilterWhere(['status' => '3'])
            ->andFilterWhere(['<', 'end_date', date('Y-m-d H:i:s')]);

        return $dataProvider;
    }

    public function searchForUser($params, $status, $id){
        $query = Bookings::find()
            ->joinWith('user', true, 'INNER JOIN')
            ->joinWith('bort', true, 'INNER JOIN');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
           'user_id'  => $id
        ]);

        if ($status == 1){
            $query->andFilterWhere(['>', 'end_date', date('Y-m-d H:i:s')])
                ->andFilterWhere(['status' => ['1', '2']]);
        } elseif ($status == 2){
            $query->andFilterWhere(['status' => '3'])
                ->orFilterWhere(['<', 'end_date', date('Y-m-d H:i:s')]);
        }

        return $dataProvider;
    }

    public function searching($params, $status)
    {
        $query = Bookings::find()
        ->joinWith('user', true, 'INNER JOIN')
        ->joinWith('bort', true, 'INNER JOIN');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andWhere([
           '>', 'start_date', date('Y-m-d H:i:s'),
        ]);
        $query->andWhere([
            'status' => $status
        ]);

        return $dataProvider;
    }

    public function search($params, $bort_id, $start_date)
    {
        $query = Bookings::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        // grid filtering conditions
        $query->andWhere([
            'bort_id' => $bort_id,
            'status' => 1
        ]);

        $query->andWhere(['or',
            ['>', 'start_date', $start_date],
            ['>', 'end_date', $start_date]]);

        $query->orderBy(['start_date' => SORT_ASC]);

        return $dataProvider;
    }

    public function searchParams($bort, $start, $end)
    {
        $query = Bookings::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        // grid filtering conditions
        $query->andWhere([
            'bort_id' => $bort,
            'status' => 1
        ]);

        $query->andWhere(['and',
            ['>', 'start_date', $start],
            ['<', 'start_date', $end]])
        ->orWhere(['and',
            ['>', 'end_date', $start],
            ['<', 'end_date', $end]]);

        $query->orderBy(['start_date' => SORT_ASC]);

        return $dataProvider;
    }
}
