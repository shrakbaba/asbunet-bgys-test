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
    public $risk_adi;
    public $kabuleden_adi;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['riskid', 'risk_adi', 'kabuleden_adi', 'aciklama', 'tarih'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'risk_adi',
            'kabuleden_adi',
        ]);
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
            'tarih' => $this->tarih,
        ]);

        $query->andFilterWhere(['like', 'riskid', $this->riskid])
            ->andFilterWhere(['like', 'aciklama', $this->aciklama]);

        $query->joinWith('risk');
        $query->andFilterWhere(['like', 'bgys_risk.risk', $this->risk_adi]);

        $query->joinWith('kabuleden0');
        $kabulEdenArama = trim((string)$this->kabuleden_adi);
        if ($kabulEdenArama !== '') {
            if (Yii::$app->params['giristipi'] == 1) {
                $query->andWhere(['or',
                    ['like', 'user.username', $kabulEdenArama],
                    ['like', 'user.id', $kabulEdenArama],
                ]);
            } else {
                $query->andWhere(['or',
                    ['like', 'user.ad', $kabulEdenArama],
                    ['like', 'user.soyad', $kabulEdenArama],
                    ['like', 'user.username', $kabulEdenArama],
                    ['like', 'bgys_risk_kabul.kabuleden', $kabulEdenArama],
                ]);
            }
        }

        return $dataProvider;
    }
}
