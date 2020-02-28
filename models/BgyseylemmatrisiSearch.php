<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgyseylemmatrisi;

/**
 * BgyseylemmatrisiSearch represents the model behind the search form of `app\models\Bgyseylemmatrisi`.
 */
class BgyseylemmatrisiSearch extends Bgyseylemmatrisi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'altdeger', 'ustdeger'], 'integer'],
            [['eylem', 'aciklama'], 'safe'],
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
        $query = Bgyseylemmatrisi::find();

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
            'altdeger' => $this->altdeger,
            'ustdeger' => $this->ustdeger,
        ]);

        $query->andFilterWhere(['like', 'eylem', $this->eylem])
            ->andFilterWhere(['like', 'aciklama', $this->aciklama]);

        return $dataProvider;
    }
}
