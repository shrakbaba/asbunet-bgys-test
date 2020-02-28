<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysfirmabilgi;

/**
 * FirmabilgiSearch represents the model behind the search form of `app\models\Firmabilgi`.
 */
class BgysfirmabilgiSearch extends Bgysfirmabilgi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['firmaadi', 'yetkilikisi', 'telefon','faaliyet_alani','mail','belge','tedarik_tipi'], 'safe'],
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
        $query = Bgysfirmabilgi::find();

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

        $query->andFilterWhere(['like', 'firmaadi', $this->firmaadi])
            ->andFilterWhere(['like', 'yetkilikisi', $this->yetkilikisi])
            ->andFilterWhere(['like', 'telefon', $this->telefon])
            ->andFilterWhere(['like', 'tedarik_tipi', $this->tedarik_tipi]);

        return $dataProvider;
    }
}
