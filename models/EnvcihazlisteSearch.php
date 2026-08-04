<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Envcihazliste;

/**
 * EnvcihazlisteSearch represents the model behind the search form of `app\models\Envcihazliste`.
 */
class EnvcihazlisteSearch extends Envcihazliste
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id' ], 'integer'],
            [['alim_tarihi', 'garanti_bitis','cihaz_turu_id', 'marka_id', 'model_id'], 'safe'],
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
        $query = Envcihazliste::find();

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
           // 'cihaz_turu_id' => $this->cihaz_turu_id,
           // 'marka_id' => $this->marka_id,
           // 'model_id' => $this->model_id,
            'alim_tarihi' => $this->alim_tarihi,
        ]);

        $query->andFilterWhere(['like', 'adet', $this->adet]);

        $query->joinwith('marka');
        $query->andFilterWhere(['like', 'marka', $this->marka_id]);

        $query->joinwith('model');
        $query->andFilterWhere(['like', 'model', $this->model_id]);

        $query->joinwith('cihazTuru');
        $query->andFilterWhere(['like', 'cihaz_turu', $this->cihaz_turu_id]);

        return $dataProvider;
    }
}
