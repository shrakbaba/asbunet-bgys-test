<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userbilgi;

/**
 * UserbilgiSearch represents the model behind the search form of `app\models\Userbilgi`.
 */
class UserbilgiSearch extends Userbilgi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kisi_id'], 'integer'],
            [['ad', 'soyad', 'email', 'tc', 'telefon', 'adres', 'dogumyili'], 'safe'],
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
        $query = Userbilgi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'kisi_id' => $this->kisi_id,
        ]);

        $query->andFilterWhere(['like', 'ad', $this->ad])
            ->andFilterWhere(['like', 'soyad', $this->soyad])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'tc', $this->tc])
            ->andFilterWhere(['like', 'telefon', $this->telefon])
            ->andFilterWhere(['like', 'adres', $this->adres])
            ->andFilterWhere(['like', 'dogumyili', $this->dogumyili]);

        return $dataProvider;
    }
}
