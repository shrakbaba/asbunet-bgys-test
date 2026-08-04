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
     * Güncelleyen kullanıcının adını filtrelemek için
     * sanal alan (veritabanında yok, join ile kullanacağız)
     */
    public $updated_by_name;   // ✅ EKSİK OLAN PROPERTY EKLENDİ
    public $risk_seviyesi;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 🔹 INTEGER alanlar
            //  - ozetdurum'u burada tuttuk (daha önce safe içindeydi)
            //  - updated_by'yi buraya da ekledim (ID olduğu için integer olmalı)
            [[
                'departman',
                'olasilik_onceki',
                'gizlilik_onceki',
                'butunluk_onceki',
                'erisilebilirlik_onceki',
                'olasilik_sonraki',
                'gizlilik_sonraki',
                'butunluk_sonraki',
                'erisilebilirlik_sonraki',
                'ozetdurum',
            ], 'integer'],

            // 🔹 SAFE alanlar (metin + tarih + sanal alanlar)
            [[
                'risk',
                'id',
                'risk_nedeni',
                'yuksek_riskin_sebebi',
                'risk_sorumlusu',
                'varlik',
                'riskdegeri_onceki',
                'riskdegeri_sonraki',
                'pasif_aciklama',
                'updated_at',      // tarih filtresi için
                'updated_by',
                'updated_by_name', // ✅ sanal alan, burada sadece safe
                'risk_seviyesi',
            ], 'safe'],
        ];
    }

    /**
     * Sanal alanları (updated_by_name) de attribute listesine ekliyoruz ki
     * $this->load($params) ile formdan gelebilsin.
     */
    public function attributes()
    {
        // parent::attributes() => bgys_risk tablosundaki alanlar
        return array_merge(parent::attributes(), [
            'updated_by_name',   // ✅ form/grid için gerekli
            'risk_seviyesi',
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
            // $query->where('0=1');
            return $dataProvider;
        }

        // 🔹 Temel integer filtreler
        $query->andFilterWhere([
            'departman'              => $this->departman,
            'olasilik_onceki'        => $this->olasilik_onceki,
            'gizlilik_onceki'        => $this->gizlilik_onceki,
            'butunluk_onceki'        => $this->butunluk_onceki,
            'erisilebilirlik_onceki' => $this->erisilebilirlik_onceki,
            'olasilik_sonraki'       => $this->olasilik_sonraki,
            'gizlilik_sonraki'       => $this->gizlilik_sonraki,
            'butunluk_sonraki'       => $this->butunluk_sonraki,
            'erisilebilirlik_sonraki'=> $this->erisilebilirlik_sonraki,
            'aktif'                  => 1,
        ]);

        if ((string)$this->ozetdurum === '4') {
            $query->andWhere(['or',
                ['bgys_risk.ozetdurum' => 4],
                ['exists', Bgysriskkabul::find()
                    ->select('id')
                    ->where('bgys_risk_kabul.riskid = bgys_risk.id')
                ],
            ]);
        } else {
            $query->andFilterWhere(['bgys_risk.ozetdurum' => $this->ozetdurum]);
        }

        // 🔹 Metin alanları
        $query->andFilterWhere(['like', 'risk', $this->risk])
            ->andFilterWhere(['like', 'bgys_risk.id', $this->id])
            ->andFilterWhere(['like', 'riskdegeri_onceki', $this->riskdegeri_onceki])
            ->andFilterWhere(['like', 'riskdegeri_sonraki', $this->riskdegeri_sonraki])
            ->andFilterWhere(['like', 'risk_nedeni', $this->risk_nedeni])
            ->andFilterWhere(['like', 'yuksek_riskin_sebebi', $this->yuksek_riskin_sebebi])
            ->andFilterWhere(['like', 'pasif_aciklama', $this->pasif_aciklama]);

        if ($this->risk_seviyesi === 'dusuk') {
            $query->andWhere(['<=', 'riskdegeri_onceki', 34]);
        } elseif ($this->risk_seviyesi === 'orta') {
            $query->andWhere(['and', ['>=', 'riskdegeri_onceki', 35], ['<', 'riskdegeri_onceki', 68]]);
        } elseif ($this->risk_seviyesi === 'yuksek') {
            $query->andWhere(['>=', 'riskdegeri_onceki', 68]);
        }

        // 🔹 Güncelleyen kullanıcının ADI ile filtre (join lazım)
        //    Bgysrisk modelinde getUpdatedByUser() ilişkisi olduğu varsayımıyla:
        //    return $this->hasOne(Userbilgi::class, ['id' => 'updated_by']);
        $query->joinWith('updatedByUser'); // ✅ relation adı Bgysrisk modelinde bu olmalı
        $guncelleyenArama = trim((string)($this->updated_by_name ?: $this->updated_by));
        if ($guncelleyenArama !== '') {
            if (Yii::$app->params['giristipi'] == 1) {
                $query->andWhere(['or',
                    ['like', 'user_bilgi.ad', $guncelleyenArama],
                    ['like', 'user_bilgi.soyad', $guncelleyenArama],
                    ['like', 'user_bilgi.kisi_id', $guncelleyenArama],
                ]);
            } else {
                $query->andWhere(['or',
                    ['like', 'user.ad', $guncelleyenArama],
                    ['like', 'user.soyad', $guncelleyenArama],
                    ['like', 'user.username', $guncelleyenArama],
                    ['like', 'bgys_risk.updated_by', $guncelleyenArama],
                ]);
            }
        }

        // 🔹 Güncellenme tarihi (gün bazlı) filtresi
        if (!empty($this->updated_at)) {
            $tarihArama = str_replace('/', '.', trim((string)$this->updated_at));
            $query->andWhere(
                "DATE_FORMAT(bgys_risk.updated_at, '%d.%m.%Y %H:%i') LIKE :updated_at",
                [':updated_at' => '%' . $tarihArama . '%']
            );
        }

        // 🔹 İlişkili tablolar için join + filtreler
        $query->joinWith('riskSorumlusu');
        $riskSorumlusuArama = trim((string)$this->risk_sorumlusu);
        if ($riskSorumlusuArama !== '') {
            $query->andWhere(['or',
                ['like', 'user.username', $riskSorumlusuArama],
                ['like', 'bgys_risk.risk_sorumlusu', $riskSorumlusuArama],
                ['in', 'bgys_risk.risk_sorumlusu', Userbilgi::find()
                    ->select('kisi_id')
                    ->where(['or',
                        ['like', 'ad', $riskSorumlusuArama],
                        ['like', 'soyad', $riskSorumlusuArama],
                    ])
                ],
            ]);
        }

        $query->joinWith('varlik0');
        $query->andFilterWhere(['like', 'varlik_adi', $this->varlik]);

        return $dataProvider;
    }

    /**
     * Pasif kayıtlar için arama
     */
    public function searchpasif($params)
    {
        $query = Bgysrisk::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'departman'              => $this->departman,
            'olasilik_onceki'        => $this->olasilik_onceki,
            'gizlilik_onceki'        => $this->gizlilik_onceki,
            'butunluk_onceki'        => $this->butunluk_onceki,
            'erisilebilirlik_onceki' => $this->erisilebilirlik_onceki,
            'olasilik_sonraki'       => $this->olasilik_sonraki,
            'gizlilik_sonraki'       => $this->gizlilik_sonraki,
            'butunluk_sonraki'       => $this->butunluk_sonraki,
            'erisilebilirlik_sonraki'=> $this->erisilebilirlik_sonraki,
            'aktif'                  => 0,
        ]);

        if ((string)$this->ozetdurum === '4') {
            $query->andWhere(['or',
                ['bgys_risk.ozetdurum' => 4],
                ['exists', Bgysriskkabul::find()
                    ->select('id')
                    ->where('bgys_risk_kabul.riskid = bgys_risk.id')
                ],
            ]);
        } else {
            $query->andFilterWhere(['bgys_risk.ozetdurum' => $this->ozetdurum]);
        }

        $query->andFilterWhere(['like', 'risk', $this->risk])
            ->andFilterWhere(['like', 'bgys_risk.id', $this->id])
            ->andFilterWhere(['like', 'riskdegeri_onceki', $this->riskdegeri_onceki])
            ->andFilterWhere(['like', 'riskdegeri_sonraki', $this->riskdegeri_sonraki])
            ->andFilterWhere(['like', 'risk_nedeni', $this->risk_nedeni])
            ->andFilterWhere(['like', 'yuksek_riskin_sebebi', $this->yuksek_riskin_sebebi])
            ->andFilterWhere(['like', 'pasif_aciklama', $this->pasif_aciklama]);

        if (!empty($this->updated_at)) {
            $tarihArama = str_replace('/', '.', trim((string)$this->updated_at));
            $query->andWhere(
                "DATE_FORMAT(bgys_risk.updated_at, '%d.%m.%Y %H:%i') LIKE :pasif_updated_at",
                [':pasif_updated_at' => '%' . $tarihArama . '%']
            );
        }

        $query->joinWith('updatedByUser');
        $guncelleyenArama = trim((string)($this->updated_by_name ?: $this->updated_by));
        if ($guncelleyenArama !== '') {
            if (Yii::$app->params['giristipi'] == 1) {
                $query->andWhere(['or',
                    ['like', 'user_bilgi.ad', $guncelleyenArama],
                    ['like', 'user_bilgi.soyad', $guncelleyenArama],
                    ['like', 'user_bilgi.kisi_id', $guncelleyenArama],
                ]);
            } else {
                $query->andWhere(['or',
                    ['like', 'user.ad', $guncelleyenArama],
                    ['like', 'user.soyad', $guncelleyenArama],
                    ['like', 'user.username', $guncelleyenArama],
                    ['like', 'bgys_risk.updated_by', $guncelleyenArama],
                ]);
            }
        }

        $query->joinWith('riskSorumlusu');
        $riskSorumlusuArama = trim((string)$this->risk_sorumlusu);
        if ($riskSorumlusuArama !== '') {
            $query->andWhere(['or',
                ['like', 'user.username', $riskSorumlusuArama],
                ['like', 'bgys_risk.risk_sorumlusu', $riskSorumlusuArama],
                ['in', 'bgys_risk.risk_sorumlusu', Userbilgi::find()
                    ->select('kisi_id')
                    ->where(['or',
                        ['like', 'ad', $riskSorumlusuArama],
                        ['like', 'soyad', $riskSorumlusuArama],
                    ])
                ],
            ]);
        }

        $query->joinWith('varlik0');
        $query->andFilterWhere(['like', 'varlik_adi', $this->varlik]);

        return $dataProvider;
    }
}
