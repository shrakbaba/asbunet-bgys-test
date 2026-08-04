<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysolaykayit;

/**
 * OlaykayitSearch represents the model behind the search form of `app\models\Olaykayit`.
 */
class BgysolaykayitSearch extends Bgysolaykayit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['konu', 'olaytarihi', 'mudahaleeden', 'yapilanmudahale', 'mudahaletarihi', 'sonuc', 'userid', 'onlem', 'belge'], 'safe'],
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
        $query = Bgysolaykayit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['olaytarihi'=>SORT_DESC]],
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
            //'userid' => $this->userid,
            'olaytarihi' => $this->olaytarihi,
            'mudahaletarihi' => $this->mudahaletarihi,
        ]);

        $query->andFilterWhere(['like', 'konu', $this->konu])
            ->andFilterWhere(['like', 'mudahaleeden', $this->mudahaleeden])
            ->andFilterWhere(['like', 'yapilanmudahale', $this->yapilanmudahale])
            ->andFilterWhere(['like', 'sonuc', $this->sonuc])
            ->andFilterWhere(['like', 'onlem', $this->sonuc]);

            $query->joinwith('user');
        $query->andFilterWhere(['like', 'username', $this->userid]);

        return $dataProvider;
    }
}
