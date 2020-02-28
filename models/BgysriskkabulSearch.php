<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysriskkabul;

/**
 * BgysriskkabulSearch represents the model behind the search form of `app\models\Bgysriskkabul`.
 */
class BgysriskkabulSearch extends Bgysriskkabul
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'riskid', 'kabuleden'], 'integer'],
            [['aciklama', 'tarih'], 'safe'],
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
        $query = Bgysriskkabul::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
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
            'riskid' => $this->riskid,
            'kabuleden' => $this->kabuleden,
            'tarih' => $this->tarih,
        ]);

        $query->andFilterWhere(['like', 'aciklama', $this->aciklama]);

        return $dataProvider;
    }
}
