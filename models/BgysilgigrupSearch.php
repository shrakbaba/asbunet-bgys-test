<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysilgigrup;

/**
 * BgysilgigrupSearch represents the model behind the search form of `app\models\Bgysilgigrup`.
 */
class BgysilgigrupSearch extends Bgysilgigrup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['grupadi', 'iletisimbirimi', 'telefon', 'grup_web', 'ilgi_konusu', 'iletisimegecme_durumu', 'etkilenecek_surecler', 'ekleme_tarihi'], 'safe'],
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
        $query = Bgysilgigrup::find();

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
            'ekleme_tarihi' => $this->ekleme_tarihi,
        ]);

        $query->andFilterWhere(['like', 'grupadi', $this->grupadi])
            ->andFilterWhere(['like', 'iletisimbirimi', $this->iletisimbirimi])
            ->andFilterWhere(['like', 'telefon', $this->telefon])
            ->andFilterWhere(['like', 'grup_web', $this->grup_web])
            ->andFilterWhere(['like', 'ilgi_konusu', $this->ilgi_konusu])
            ->andFilterWhere(['like', 'iletisimegecme_durumu', $this->iletisimegecme_durumu])
            ->andFilterWhere(['like', 'etkilenecek_surecler', $this->etkilenecek_surecler]);

        return $dataProvider;
    }
}
