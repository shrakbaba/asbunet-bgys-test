<?php 

namespace yii\helpers;
use Yii;
use yii\helpers\SoapClient;
use yii\helpers\Html;
use app\models\Bgyslogs;
use app\models\Bgysdiftalep;
use app\models\Bgysizlemeolcme;
use app\models\Bgysizlemesonucu;
use app\models\Userbilgi;


class bgys
{
	public static function cronErisiminiDogrula()
	{
		$beklenenAnahtar = Yii::$app->params['cronKey'] ?? '';
		$gelenAnahtar = Yii::$app->request->headers->get('X-BGYS-Cron-Key', '');
		$istemciIp = Yii::$app->request->getUserIP();

		$anahtarGecerli = $beklenenAnahtar !== '' && $gelenAnahtar !== ''
			&& hash_equals($beklenenAnahtar, $gelenAnahtar);

		if (!$anahtarGecerli) {
			Yii::warning('Yetkisiz cron isteği: ' . $istemciIp, 'security');
			throw new \yii\web\ForbiddenHttpException('Yetkisiz cron isteği.');
		}
	}

	public static function logtut($controller,$action,$userid,$islem,$not)
	{
		//echo ($controller."  ".$action."   ".$userid."   ".$not);exit;
		$model = new Bgyslogs();
		$model->controller=$controller;
		$model->action=$action;
		$model->userid=intval($userid);
		$model->islem=($islem);
		$model->not=$not;
		$model->save();
	}
	public static function Olcmesorumlumu($id)
	{
		$olcme=Bgysizlemeolcme::find()->where(['id'=>$id])->one();
		//echo "<pre>";var_dump($dif);Exit;
		//echo Yii::$app->user->identity->id."<br>";
		//echo $olcme->sorumlu;exit;

		if ($olcme and $olcme->sorumlu==Yii::$app->user->identity->id ) {
			return true;
		}else
			return null;
	}
	public static function islemgirilmemis($id)
	{
		$olcme=Bgysizlemesonucu::find()->where(['izlemeid'=>$id])->one();
		//echo "<pre>";var_dump($dif);Exit;
		//echo Yii::$app->user->identity->id."<br>";
		//echo $olcme->sorumlu;exit;

		if ($olcme ) {
			return true;
		}else
			return null;
	}
	
	public static function Difiddenid($id)
	{
		$dif=Bgysdiftalep::find()->where(['dif_no'=>$id])->one();
		//echo "<pre>";var_dump($dif);Exit;
		if ($dif) {
			return $dif->id;
		}else
			return null;
	}	
    
	public static function varlikdegeri($id)
	{
	  $degerler = array(1 =>"Düşük" ,2=>"Orta",3=>'Yüksek',4=>'Çok Yüksek');
	  return $degerler[$id];
	}

	public static function tedarikcitipi($id)
	{
	  $degerler = array(1 =>"Hizmet" ,2=>"Malzeme",3=>'Servis',4=>'Yüksek Teknoloji',5=>'Yazılım',6=>'Lisans');
	  return @$degerler[$id];
	}

	public static function garantibildir($data, $maillistesi)
	{	

		return Yii::$app->mailer->compose('garantisuresi', 
			[
				//'imageFileName' => 'https://asbunet.asbu.edu.tr/uploads/sem_turk_logo.jpg',
				'maillistesi'=>$maillistesi,
				'data'=>$data,
			])
		    ->setFrom([Yii::$app->params['mailadresi']=>'Garanti Süresi'])
		    ->setTo($maillistesi)
		    ->setSubject('Garanti Süresi Hatırlatma')
		    ->send();
	}

	public static function mailGrubu($grupAdi)
	{
		$gruplar = Yii::$app->params['mailGruplari'] ?? [];
		$liste = $gruplar[$grupAdi] ?? [];
		return self::mailListesiniTemizle($liste);
	}

	public static function mailListesiOlustur($grupAdi, $ekAdresler = [])
	{
		return self::mailListesiniTemizle(array_merge(self::mailGrubu($grupAdi), (array)$ekAdresler));
	}

	public static function zimmetEmail($zimmetKisiId)
	{
		if (!$zimmetKisiId) {
			return null;
		}

		$userBilgi = Userbilgi::find()->where(['kisi_id' => (int)$zimmetKisiId])->one();
		if ($userBilgi !== null && !empty($userBilgi->email)) {
			return $userBilgi->email;
		}

		try {
			$identityClass = \Edvlerblog\Adldap2\model\UserDbLdap::className();
			$identity = $identityClass::findOne((int)$zimmetKisiId);
			if ($identity !== null) {
				Userbilgi::adBilgileriniSenkronla($identity);
				$userBilgi = Userbilgi::find()->where(['kisi_id' => (int)$zimmetKisiId])->one();
				if ($userBilgi !== null && !empty($userBilgi->email)) {
					return $userBilgi->email;
				}
			}
		} catch (\Throwable $e) {
			Yii::warning('Zimmet kullanıcısı e-posta bilgisi okunamadı: ' . $e->getMessage(), 'security');
		}

		return null;
	}

	public static function mailListesiniTemizle($liste)
	{
		$temizListe = [];
		foreach ((array)$liste as $mail) {
			$mail = trim((string)$mail);
			if ($mail !== '' && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				$temizListe[strtolower($mail)] = $mail;
			}
		}
		return array_values($temizListe);
	}

	public static function yenivm($maillistesi, $vmname)
	{
		return Yii::$app->mailer->compose('yenivm',
			[
				//'imageFileName' => 'https://asbunet.asbu.edu.tr/uploads/sem_turk_logo.jpg',
				'vmname'=>$vmname,
			])
		    ->setFrom([Yii::$app->params['mailadresi']=>'Yeni VM'])
		    ->setTo($maillistesi)
		    ->setSubject('Yeni VM Açıldı')
		    ->send();
	}

	public static function mailikapat($uyaritarihi, $mailhesabi, $y, $maillistesi, $ayrilistarihi)
	{	
		return Yii::$app->mailer->compose('mailikapat', 
			[
				//'imageFileName' => 'https://asbunet.asbu.edu.tr/uploads/sem_turk_logo.jpg',
				'maillistesi'=>$maillistesi,
				'uyaritarihi'=>$uyaritarihi,
				'mailhesabi'=>$mailhesabi,
				'y'=>$y,
				'ayrilistarihi'=>$ayrilistarihi
			])
		    ->setFrom([Yii::$app->params['mailadresi']=>'Hesap Kapatma'])
		    ->setTo($maillistesi)
		    ->setSubject('Hesap Kapatma Hatırlatması')
		    ->send();
	}

	public static function bakima1hafta($uyaritarihi, $marka, $model, $key, $service_tag, $maillistesi, $bakimtarihi)
	{	
		return Yii::$app->mailer->compose('bakima1hafta', 
			[
				//'imageFileName' => 'https://asbunet.asbu.edu.tr/uploads/sem_turk_logo.jpg',
				'maillistesi'=>$maillistesi,
				'uyaritarihi'=>$uyaritarihi,
				'marka'=>$marka,
				'model'=>$model,
				'key'=>$key,
				'service_tag'=>$service_tag,
				'bakimtarihi'=>$bakimtarihi
			])
		    ->setFrom([Yii::$app->params['mailadresi']=>'Bakım Hatırlatma'])
		    ->setTo($maillistesi)
		    ->setSubject('Yaklaşan Cihaz Bakımı')
		    ->send();
	}

	public static function olaykayitbildirim($email,$konu,$sonuc)
	{	
		return Yii::$app->mailer->compose('bgysolaykayit', 
			[
				//'imageFileName' => 'https://asbunet.asbu.edu.tr/uploads/sem_turk_logo.jpg',
				'email'=>$email,
				'konu'=>$konu,
				'sonuc'=>$sonuc
			])
		    ->setFrom([Yii::$app->params['mailadresi']=>'Olay Kayıt Bildirimi'])
		    ->setTo($email)
		    ->setSubject('Olay Kaydı Açıldı')
		    ->send();
	}

	public static function ozetdurum($id)
	{
	  $degerler = array(1 => "Risk Azalmış" ,2=>'Risk Artmış',3=>'Değişim Yok',4=>'Risk Kabul');
	  return isset($degerler[$id]) ? $degerler[$id] : '';
	}


	


}
