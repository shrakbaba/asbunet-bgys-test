<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysvarlikenvanteri;

/**
 * BgysvarlikenvanteriSearch represents the model behind the search form of `app\models\Bgysvarlikenvanteri`.
 */
class BgysvarlikenvanteriSearch extends Bgysvarlikenvanteri
{
    public $needs_completion;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bilgi_sinifi', 'kategori', 'gizlilik', 'butunluk', 'erisilebilirlik', 'varlik_degeri', 'needs_completion'], 'integer'],
            [['departman', 'varlik_adi', 'lokasyon', 'varlik_sahibi', 'aciklama', 'asset_type'], 'safe'],
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
        $query = Bgysvarlikenvanteri::find();

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
            'bilgi_sinifi' => $this->bilgi_sinifi,
            'kategori' => $this->kategori,
            'gizlilik' => $this->gizlilik,
            'butunluk' => $this->butunluk,
            'erisilebilirlik' => $this->erisilebilirlik,
            'varlik_degeri' => $this->varlik_degeri,
            'asset_type' => $this->asset_type,
        ]);

        $query->andFilterWhere(['like', 'departman', $this->departman])
            ->andFilterWhere(['like', 'varlik_adi', $this->varlik_adi])
            ->andFilterWhere(['like', 'lokasyon', $this->lokasyon])
            ->andFilterWhere(['like', 'varlik_sahibi', $this->varlik_sahibi]);

        if ($this->needs_completion) {
            $query->andWhere(['not', ['source_device_id' => null]])->andWhere(['or',
                ['departman' => null], ['bilgi_sinifi' => null], ['lokasyon' => null],
                ['gizlilik' => null], ['butunluk' => null], ['erisilebilirlik' => null], ['varlik_degeri' => null],
            ]);
        }

        return $dataProvider;
    }
}
