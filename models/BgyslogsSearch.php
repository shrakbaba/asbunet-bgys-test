<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgyslogs;

/**
 * BgyslogsSearch represents the model behind the search form of `app\models\Bgyslogs`.
 */
class BgyslogsSearch extends Bgyslogs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['userid', 'controller', 'action', 'date', 'not', 'islem', 'actor', 'result', 'ip_address', 'correlation_id'], 'safe'],
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
    public function search($params)
    {
        $query = Bgyslogs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'userid' => $this->userid,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'not', $this->not])
            ->andFilterWhere(['like', 'islem', $this->islem])
            ->andFilterWhere(['like', 'result', $this->result])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'correlation_id', $this->correlation_id]);

            $query->joinwith('logyapan');
            if ($this->userid !== null && $this->userid !== '') {
                $query->andWhere(['or', ['like', 'username', $this->userid], ['like', 'actor', $this->userid]]);
            }

        return $dataProvider;
    }
}
