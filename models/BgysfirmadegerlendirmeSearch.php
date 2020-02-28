<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysfirmadegerlendirme;

/**
 * FirmadegerlendirmeSearch represents the model behind the search form of `app\models\Firmadegerlendirme`.
 */
class BgysfirmadegerlendirmeSearch extends Bgysfirmadegerlendirme
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kriter1', 'kriter2', 'kriter3', 'kriter4', 'kriter5', 'kriter6', 'kriter7', 'kriter8', 'kriter9', 'kriter10'], 'integer'],
            [['firmaid', 'degerlendiren'], 'safe'],
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
        $query = Bgysfirmadegerlendirme::find();

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
            'kriter1' => $this->kriter1,
            'kriter2' => $this->kriter2,
            'kriter3' => $this->kriter3,
            'kriter4' => $this->kriter4,
            'kriter5' => $this->kriter5,
            'kriter6' => $this->kriter6,
            'kriter7' => $this->kriter7,
            'kriter8' => $this->kriter8,
            'kriter9' => $this->kriter9,
            'kriter10' => $this->kriter10,
        ]);

        $dataProvider->setSort(['defaultOrder' => [
            'toplam' => SORT_DESC
            ]
        ]);

        //$query->andFilterWhere(['like', 'firmaid', $this->firmaid]);
        //->andFilterWhere(['like', 'degerlendiren', $this->degerlendiren]);

        $query->joinwith('user');
        $query->andFilterWhere(['like', 'username', $this->degerlendiren]);

        $query->joinwith('firmabilgi');
        $query->andFilterWhere(['like', 'firmaadi', $this->firmaid]);

        return $dataProvider;
    }
}
