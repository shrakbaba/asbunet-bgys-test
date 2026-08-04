<?php

namespace app\models;

use Yii;

class Userbilgi extends \yii\db\ActiveRecord
{
    public $username;
    
    public static function tableName()
    {
        return 'user_bilgi';
    }

    public function rules()
    {
        return [
            [['ad', 'soyad', 'email', 'kisi_id'], 'required'],
            [['kisi_id'], 'integer'],
            [['ad', 'soyad', 'email', 'birim', 'adres'], 'string', 'max' => 255],
            [['tc'], 'string', 'max' => 11],
            [['telefon'], 'string', 'max' => 15],
            [['dogumyili'], 'string', 'max' => 4],
            [['email'], 'unique'],
            //[['tc'], 'unique'],
            //[['telefon'], 'unique'],
            [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' => \Edvlerblog\Adldap2\model\UserDbLdap::className()::className(), 'targetAttribute' => ['kisi_id' => 'id']],
            /*(Yii::$app->params['giristipi']==1) 
            ? [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' =>\Edvlerblog\Adldap2\model\UserDbLdap::className() , 'targetAttribute' => ['kisi_id' => 'id']] 
            : [['kisi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userdb::className(), 'targetAttribute' => ['kisi_id' => 'id']],*/
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Kullanıcı Adı',
            'ad' => 'Ad',
            'soyad' => 'Soyad',
            'email' => 'Email',
            'birim' => 'Birim',
            'tc' => 'TC',
            'telefon' => 'Telefon',
            'adres' => 'Adres',
            'dogumyili' => 'Doğum Yılı',
            'kisi_id' => 'Kişi ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKisi()
    {
        //return $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className()::className(), ['id' => 'kisi_id']);
        return (Yii::$app->params['giristipi']==1) ?
            $this->hasOne(\Edvlerblog\Adldap2\model\UserDbLdap::className(), ['id' => 'kisi_id'])
        :
            $this->hasOne(Userdb::className(), ['id' => 'kisi_id']);

    }

    public static function adBilgileriniSenkronla($identity)
    {
        if (!$identity || empty($identity->id)) {
            return null;
        }

        $model = self::find()->where(['kisi_id' => (int)$identity->id])->one();
        if ($model === null) {
            $model = new self();
            $model->kisi_id = (int)$identity->id;
        }

        $username = $identity->username ?? '';
        $adSoyad = explode('.', $username, 2);

        $model->ad = $adSoyad[0] ?? $username;
        $model->soyad = $adSoyad[1] ?? $username;
        $model->email = $username ? $username . '@asbu.edu.tr' : $model->email;

        try {
            if (method_exists($identity, 'queryLdapUserObject')) {
                $ldapUser = $identity->queryLdapUserObject();
                if ($ldapUser) {
                    $ad = self::ilkLdapDegeri($ldapUser, 'givenname');
                    $soyad = self::ilkLdapDegeri($ldapUser, 'sn');
                    $email = self::ilkLdapDegeri($ldapUser, 'mail');
                    $birim = self::ilkLdapDegeri($ldapUser, 'department');
                    $telefon = self::ilkLdapDegeri($ldapUser, 'telephonenumber') ?: self::ilkLdapDegeri($ldapUser, 'mobile');
                    $adres = self::ilkLdapDegeri($ldapUser, 'streetaddress') ?: self::ilkLdapDegeri($ldapUser, 'postaladdress');
                    $tc = self::ilkLdapDegeri($ldapUser, 'employeeid');
                    $dogumyili = self::ilkLdapDegeri($ldapUser, 'extensionattribute1');

                    if ($ad) {
                        $model->ad = $ad;
                    }
                    if ($soyad) {
                        $model->soyad = $soyad;
                    }
                    if ($email) {
                        $model->email = $email;
                    }
                    if ($birim) {
                        $model->birim = $birim;
                    }
                    if ($telefon && !$model->telefon) {
                        $model->telefon = mb_substr($telefon, 0, 15, 'UTF-8');
                    }
                    if ($adres && !$model->adres) {
                        $model->adres = $adres;
                    }
                    if ($tc && !$model->tc) {
                        $model->tc = mb_substr($tc, 0, 11, 'UTF-8');
                    }
                    if ($dogumyili && !$model->dogumyili) {
                        $model->dogumyili = mb_substr($dogumyili, 0, 4, 'UTF-8');
                    }
                }
            }
        } catch (\Throwable $e) {
            Yii::warning('AD kullanıcı bilgisi okunamadı: ' . $e->getMessage(), 'security');
        }

        self::asbunetBilgileriyleBosAlanlariTamamla($model, $username);

        try {
            $emailSahibi = self::find()
                ->where(['email' => $model->email])
                ->andWhere(['<>', 'kisi_id', (int)$identity->id])
                ->one();
            if ($emailSahibi !== null && $username) {
                $model->email = $username . '@asbu.edu.tr';
            }

            if (!$model->save()) {
                Yii::warning('Kullanıcı bilgisi otomatik güncellenemedi: ' . json_encode($model->errors, JSON_UNESCAPED_UNICODE), 'security');
            }
        } catch (\Throwable $e) {
            Yii::warning('Kullanıcı bilgisi otomatik güncellenemedi: ' . $e->getMessage(), 'security');
        }

        return $model;
    }

    private static function ilkLdapDegeri($ldapUser, $alan)
    {
        $deger = $ldapUser->getAttribute($alan);
        return is_array($deger) ? ($deger[0] ?? null) : $deger;
    }

    private static function asbunetBilgileriyleBosAlanlariTamamla(self $model, $username)
    {
        if (!$username || !Yii::$app->has('dbasbunet')) {
            return;
        }

        try {
            $asbunetBilgi = (new \yii\db\Query())
                ->select(['ub.ad', 'ub.soyad', 'ub.email', 'ub.birim', 'ub.tc', 'ub.telefon', 'ub.adres', 'ub.dogumyili'])
                ->from('user_bilgi ub')
                ->innerJoin('user u', 'u.id = ub.kisi_id')
                ->where(['u.username' => $username])
                ->one(Yii::$app->dbasbunet);

            if (!$asbunetBilgi) {
                return;
            }

            foreach (['ad', 'soyad', 'email', 'birim', 'tc', 'telefon', 'adres', 'dogumyili'] as $alan) {
                if (!$model->$alan && !empty($asbunetBilgi[$alan])) {
                    $model->$alan = $asbunetBilgi[$alan];
                }
            }
        } catch (\Throwable $e) {
            Yii::warning('Asbunet kullanıcı bilgisi okunamadı: ' . $e->getMessage(), 'security');
        }
    }
}
