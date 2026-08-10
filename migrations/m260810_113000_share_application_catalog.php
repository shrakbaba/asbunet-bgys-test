<?php

use yii\db\Migration;

class m260810_113000_share_application_catalog extends Migration
{
    public function safeUp()
    {
        $typeIds = (new \yii\db\Query())->select('id')->from('{{%env_cihaz_turu}}')
            ->where(['like', 'cihaz_turu', 'Kritik veri'])
            ->andWhere(['like', 'cihaz_turu', 'uygulamalar'])
            ->column($this->db);
        if (!$typeIds) {
            return;
        }

        $brandIds = (new \yii\db\Query())->select('marka_id')->distinct()->from('{{%env_cihaz_liste}}')
            ->where(['cihaz_turu_id' => $typeIds])->andWhere(['not', ['marka_id' => null]])->column($this->db);
        $modelIds = (new \yii\db\Query())->select('model_id')->distinct()->from('{{%env_cihaz_liste}}')
            ->where(['cihaz_turu_id' => $typeIds])->andWhere(['not', ['model_id' => null]])->column($this->db);

        foreach ($typeIds as $typeId) {
            foreach ($brandIds as $brandId) {
                $this->db->createCommand()->upsert('{{%env_cihaz_turu_marka}}', [
                    'cihaz_turu_id' => $typeId, 'marka_id' => $brandId,
                ], false)->execute();
            }
            foreach ($modelIds as $modelId) {
                $this->db->createCommand()->upsert('{{%env_cihaz_turu_model}}', [
                    'cihaz_turu_id' => $typeId, 'model_id' => $modelId,
                ], false)->execute();
            }
        }
    }

    public function safeDown()
    {
        return false;
    }
}
