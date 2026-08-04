<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mailkapat;

/**
 * MailkapatSearch represents the model behind the search form of `app\models\Mailkapat`.
 */
class MailkapatSearch extends Mailkapat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kapatildi'], 'integer'],
            [['mailhesabi', 'ayrilistarihi'], 'safe'],
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
        $query = Mailkapat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $ayrilisTarihi = $this->ayrilistarihi;
        if ($ayrilisTarihi && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $ayrilisTarihi)) {
            $tarihParcalari = explode('/', $ayrilisTarihi);
            $ayrilisTarihi = $tarihParcalari[2].'-'.$tarihParcalari[1].'-'.$tarihParcalari[0];
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kapatildi' => $this->kapatildi,
        ]);

        $query->andFilterWhere(['like', 'mailhesabi', $this->mailhesabi])
            ->andFilterWhere(['like', 'ayrilistarihi', $ayrilisTarihi]);

        return $dataProvider;
    }
}
