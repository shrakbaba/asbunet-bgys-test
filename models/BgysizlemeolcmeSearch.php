<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bgysizlemeolcme;

/**
 * BgysizlemeolcmeSearch represents the model behind the search form of `app\models\Bgysizlemeolcme`.
 */
class BgysizlemeolcmeSearch extends Bgysizlemeolcme
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'yil', 'hedef_degeri', 'olcum_sikligi', 'sorumlu'], 'integer'],
            [['kontrol', 'planlanan_tarihi', 'olcum_sonucu', 'kontrol_kriteri', 'olusturma_tarihi'], 'safe'],
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
        $query = Bgysizlemeolcme::find();

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
            'yil' => $this->yil,
            'hedef_degeri' => $this->hedef_degeri,
            'olcum_sikligi' => $this->olcum_sikligi,
            'planlanan_tarihi' => $this->planlanan_tarihi,
            'sorumlu' => $this->sorumlu,
            'olusturma_tarihi' => $this->olusturma_tarihi,
        ]);

        $query->andFilterWhere(['like', 'kontrol', $this->kontrol])
            ->andFilterWhere(['like', 'olcum_sonucu', $this->olcum_sonucu])
            ->andFilterWhere(['like', 'kontrol_kriteri', $this->kontrol_kriteri]);

        return $dataProvider;
    }
}
