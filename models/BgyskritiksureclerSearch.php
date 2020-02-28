<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgyskritiksurecler;

/**
 * BgyskritiksureclerSearch represents the model behind the search form of `app\models\Bgyskritiksurecler`.
 */
class BgyskritiksureclerSearch extends Bgyskritiksurecler
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'keks', 'kevk'], 'integer'],
            [['surec', 'etkisi', 'ilkaksiyon', 'yedeklilik', 'ulasilacaklar', 'ekleme_tarihi'], 'safe'],
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
        $query = Bgyskritiksurecler::find();

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
            'keks' => $this->keks,
            'kevk' => $this->kevk,
            'ekleme_tarihi' => $this->ekleme_tarihi,
        ]);

        $query->andFilterWhere(['like', 'surec', $this->surec])
            ->andFilterWhere(['like', 'etkisi', $this->etkisi])
            ->andFilterWhere(['like', 'ilkaksiyon', $this->ilkaksiyon])
            ->andFilterWhere(['like', 'yedeklilik', $this->yedeklilik])
            ->andFilterWhere(['like', 'ulasilacaklar', $this->ulasilacaklar]);

        return $dataProvider;
    }
}
