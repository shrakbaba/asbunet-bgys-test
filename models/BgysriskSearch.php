<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysrisk;

/**
 * BgysriskSearch represents the model behind the search form of `app\models\Bgysrisk`.
 */
class BgysriskSearch extends Bgysrisk
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'varlik', 'departman', 'olasilik_onceki', 'gizlilik_onceki', 'butunluk_onceki', 'erisilebilirlik_onceki', 'riskdegeri_onceki', 'olasilik_sonraki', 'gizlilik_sonraki', 'butunluk_sonraki', 'erisilebilirlik_sonraki', 'riskdegeri_sonraki'], 'integer'],
            [['id','risk', 'risk_nedeni', 'yuksek_riskin_sebebi','ozetdurum','risk_sorumlusu'], 'safe'],
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
        $query = Bgysrisk::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
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
            'bgys_risk.id' => $this->id,
            'varlik' => $this->varlik,
            'departman' => $this->departman,
            //'risk_sorumlusu' => $this->risk_sorumlusu,
            'olasilik_onceki' => $this->olasilik_onceki,
            'gizlilik_onceki' => $this->gizlilik_onceki,
            'butunluk_onceki' => $this->butunluk_onceki,
            'erisilebilirlik_onceki' => $this->erisilebilirlik_onceki,
            'riskdegeri_onceki' => $this->riskdegeri_onceki,
            'olasilik_sonraki' => $this->olasilik_sonraki,
            'gizlilik_sonraki' => $this->gizlilik_sonraki,
            'butunluk_sonraki' => $this->butunluk_sonraki,
            'erisilebilirlik_sonraki' => $this->erisilebilirlik_sonraki,
            'riskdegeri_sonraki' => $this->riskdegeri_sonraki,
            'ozetdurum' => $this->ozetdurum,
        ]);

        $query->andFilterWhere(['like', 'risk', $this->risk])
            ->andFilterWhere(['like', 'risk_nedeni', $this->risk_nedeni])
            ->andFilterWhere(['like', 'yuksek_riskin_sebebi', $this->yuksek_riskin_sebebi]);
          //  ->andFilterWhere(['like', 'risksorumlusu.username', $this->risk_sorumlusu]);


        $query->joinwith('riskSorumlusu');
        $query->andFilterWhere(['like', 'username', $this->risk_sorumlusu]);


        return $dataProvider;
    }
}
