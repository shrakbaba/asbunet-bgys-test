<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysyedeklemelistesi;

/**
 * BgysyedeklemelistesiSearch represents the model behind the search form of `app\models\Bgysyedeklemelistesi`.
 */
class BgysyedeklemelistesiSearch extends Bgysyedeklemelistesi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sorumlu', 'yedeklemesekli', 'periyodu', 'yedeklemezamani'], 'integer'],
            [['yedekalinacak', 'yedekleme_yontemi', 'yedeklemeyeri', 'olusturma_tarihi'], 'safe'],
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
        $query = Bgysyedeklemelistesi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['periyodu'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 50,
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
            'sorumlu' => $this->sorumlu,
            'yedeklemesekli' => $this->yedeklemesekli,
            'periyodu' => $this->periyodu,
            'yedeklemezamani' => $this->yedeklemezamani,
            'olusturma_tarihi' => $this->olusturma_tarihi,
        ]);

        $query->andFilterWhere(['like', 'yedekalinacak', $this->yedekalinacak])
            ->andFilterWhere(['like', 'yedekleme_yontemi', $this->yedekleme_yontemi])
            ->andFilterWhere(['like', 'yedeklemeyeri', $this->yedeklemeyeri]);

        return $dataProvider;
    }
}
