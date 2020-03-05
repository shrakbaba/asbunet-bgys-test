<?php 

namespace yii\helpers;
use Yii;
use yii\helpers\SoapClient;
use yii\helpers\Html;
use app\models\Bgyslogs;
use app\models\Bgysdiftalep;
use app\models\Bgysizlemeolcme;


class bgys
{
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
		    ->setFrom(['bimteknik@kastamonu.edu.tr'=>'Olay Kayıt Bildirimi'])
		    ->setTo($email)
		    ->setSubject('Olay Kaydı Açıldı')
		    ->send();
	}

	public static function ozetdurum($id)
	{
	  $degerler = array(1 => "Risk Azalmış" ,2=>'Risk Artmış',3=>'Değişim Yok');
	  return $degerler[$id];
	}


	


}
