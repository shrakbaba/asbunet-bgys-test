<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yenihost_bildir".
 *
 * @property int $id
 * @property string $vm_name
 * @property int $zabbix
 * @property int $kaspersky
 * @property int $ipmanage
 * @property int $paloalto
 * @property string $tarihi
 * @property string $json
 */
class Yenihostbildir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yenihost_bildir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vm_name'], 'required'],
            [['zabbix', 'kaspersky', 'ipmanage', 'paloalto'], 'integer'],
            [['tarihi'], 'safe'],
            [['vm_name'], 'string', 'max' => 255],
            [['json'], 'string', 'max' => 1500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vm_name' => 'VM Adı',
            'zabbix' => 'Zabbix',
            'kaspersky' => 'Kaspersky',
            'ipmanage' => 'IP Manage',
            'paloalto' => 'Palo Alto',
            'tarihi' => 'Kayıt Tarihi',
            'json' => 'Json',
        ];
    }
}
