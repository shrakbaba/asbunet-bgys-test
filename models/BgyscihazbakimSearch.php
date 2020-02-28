<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgyscihazbakim;

/**
 * BgyscihazbakimSearch represents the model behind the search form of `app\models\Bgyscihazbakim`.
 */
class BgyscihazbakimSearch extends Bgyscihazbakim
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cihazid', 'sorumlu'], 'integer'],
            [['periyod', 'bakimformlari', 'sozlesme', 'kayittarihi', 'guncellemetarihi'], 'safe'],
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
        $query = Bgyscihazbakim::find();

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
            'cihazid' => $this->cihazid,
            'sorumlu' => $this->sorumlu,
            'kayittarihi' => $this->kayittarihi,
            'guncellemetarihi' => $this->guncellemetarihi,
        ]);

        $query->andFilterWhere(['like', 'periyod', $this->periyod])
            ->andFilterWhere(['like', 'bakimformlari', $this->bakimformlari])
            ->andFilterWhere(['like', 'sozlesme', $this->sozlesme]);

        return $dataProvider;
    }
}
