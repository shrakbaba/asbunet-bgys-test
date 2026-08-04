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
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kisi_id', 'username', 'ad', 'soyad', 'email', 'birim', 'tc', 'telefon', 'adres', 'dogumyili'], 'safe'],
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
        $query = Userbilgi::find()->alias('ub')->joinWith(['kisi k']);

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
        $query->andFilterWhere(['like', 'ub.id', $this->id])
            ->andFilterWhere(['like', 'ub.kisi_id', $this->kisi_id])
            ->andFilterWhere(['like', 'k.username', $this->username])
            ->andFilterWhere(['like', 'ub.ad', $this->ad])
            ->andFilterWhere(['like', 'ub.soyad', $this->soyad])
            ->andFilterWhere(['like', 'ub.email', $this->email])
            ->andFilterWhere(['like', 'ub.birim', $this->birim])
            ->andFilterWhere(['like', 'ub.tc', $this->tc])
            ->andFilterWhere(['like', 'ub.telefon', $this->telefon])
            ->andFilterWhere(['like', 'ub.adres', $this->adres])
            ->andFilterWhere(['like', 'ub.dogumyili', $this->dogumyili]);

        return $dataProvider;
    }
}
