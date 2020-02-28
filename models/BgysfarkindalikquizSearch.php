<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysfarkindalikquiz;

/**
 * BgysfarkindalikquizSearch represents the model behind the search form of `app\models\Bgysfarkindalikquiz`.
 */
class BgysfarkindalikquizSearch extends Bgysfarkindalikquiz
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['cevaplayan', 'ip', 'cevaplamatarihi', 'cevaplar','puan'], 'safe'],
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
        $query = Bgysfarkindalikquiz::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'cevaplamatarihi' => $this->cevaplamatarihi,
        ]);

        $query->andFilterWhere(['like', 'cevaplayan', $this->cevaplayan])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'puan', $this->puan])
            ->andFilterWhere(['like', 'cevaplar', $this->cevaplar]);

        return $dataProvider;
    }
}
