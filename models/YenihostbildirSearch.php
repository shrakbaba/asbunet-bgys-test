<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Yenihostbildir;

/**
 * YenihostbildirSearch represents the model behind the search form of `app\models\Yenihostbildir`.
 */
class YenihostbildirSearch extends Yenihostbildir
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'zabbix', 'kaspersky', 'ipmanage', 'paloalto'], 'integer'],
            [['vm_name', 'tarihi', 'json'], 'safe'],
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
        $query = Yenihostbildir::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'tarihi' => SORT_DESC,
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $tarihi = $this->tarihi;
        if ($tarihi && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $tarihi)) {
            $tarihParcalari = explode('/', $tarihi);
            $tarihi = $tarihParcalari[2].'-'.$tarihParcalari[1].'-'.$tarihParcalari[0];
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'zabbix' => $this->zabbix,
            'kaspersky' => $this->kaspersky,
            'ipmanage' => $this->ipmanage,
            'paloalto' => $this->paloalto,
        ]);

        $query->andFilterWhere(['like', 'vm_name', $this->vm_name])
            ->andFilterWhere(['like', 'tarihi', $tarihi])
            ->andFilterWhere(['like', 'json', $this->json]);

        return $dataProvider;
    }
}
