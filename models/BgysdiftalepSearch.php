<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysdiftalep;

/**
 * BgysdiftalepSearch represents the model behind the search form of `app\models\Bgysdiftalep`.
 */
class BgysdiftalepSearch extends Bgysdiftalep
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'durum'], 'integer'],
            [['dif_no', 'talep_tarihi', 'talep_eden', 'dif_konusu', 'planlanan_tarih'], 'safe'],
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
        $query = Bgysdiftalep::find();

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

        $dataProvider->setSort(['defaultOrder' => [
            'dif_no' => SORT_DESC,
           // 'planlanan_tarih' => SORT_DESC,
            ]
        ]);

        /*$a=null;
        if (json_decode($model->risk_iliskisi)) {
            foreach (json_decode($model->risk_iliskisi) as $key => $value) {
                $a=$a." <b>Risk ".$value."</b> ".@Bgysrisk::find()->where(['id'=>$value])->one()->risk."<br>";                            
            } 
        }
        echo "<pre>";var_dump($query);exit;*/


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'talep_tarihi' => $this->talep_tarihi,
            //$a => $this->risk_iliskisi,
        ]);

        if (!empty($this->planlanan_tarih)) {
            $planlananTarihArama = str_replace('.', '/', trim((string)$this->planlanan_tarih));
            $query->andWhere(
                "DATE_FORMAT(planlanan_tarih, '%d/%m/%Y') LIKE :planlanan_tarih",
                [':planlanan_tarih' => '%' . $planlananTarihArama . '%']
            );
        }

        if ($this->durum !== null && $this->durum !== '') {
            if ((int)$this->durum === 1) {
                $query->andWhere(['durum' => 1]);
            } else {
                $query->andWhere(['or', ['durum' => 0], ['durum' => null]]);
            }
        }

        $query->andFilterWhere(['like', 'dif_no', $this->dif_no])
            ->andFilterWhere(['like', 'talep_eden', $this->talep_eden])
            ->andFilterWhere(['like', 'dif_konusu', $this->dif_konusu]);

        return $dataProvider;
    }
}
