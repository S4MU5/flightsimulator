<?php

namespace app\modules\admin\models;

use app\models\Issues;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Borts;

/**
 * BortsSearch represents the model behind the search form of `app\models\Borts`.
 */
class IssueSearch extends Issues
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bort_id', 'issue', 'answer'], 'required'],
            [['bort_id'], 'integer'],
            [['issue', 'answer'], 'string', 'max' => 255],
            [['bort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Borts::class, 'targetAttribute' => ['bort_id' => 'id']],
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
    public function search($params, $bort_id)
    {
        $query = Issues::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere([
           'bort_id' => $bort_id,
        ]);

        $query->andFilterWhere(['like', 'issue', $this->issue])
            ->andFilterWhere(['like', 'answer', $this->answer]);

        return $dataProvider;
    }
}
