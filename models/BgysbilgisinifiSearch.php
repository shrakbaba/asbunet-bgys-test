<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysbilgisinifi;

/**
 * BgysbilgisinifiSearch represents the model behind the search form of `app\models\Bgysbilgisinifi`.
 */
class BgysbilgisinifiSearch extends Bgysbilgisinifi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['adi', 'aciklama', 'erisimhaklari', 'saklama', 'iletim', 'imha'], 'safe'],
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
        $query = Bgysbilgisinifi::find();

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
        ]);

        $query->andFilterWhere(['like', 'adi', $this->adi])
            ->andFilterWhere(['like', 'aciklama', $this->aciklama])
            ->andFilterWhere(['like', 'erisimhaklari', $this->erisimhaklari])
            ->andFilterWhere(['like', 'saklama', $this->saklama])
            ->andFilterWhere(['like', 'iletim', $this->iletim])
            ->andFilterWhere(['like', 'imha', $this->imha]);

        return $dataProvider;
    }
}
