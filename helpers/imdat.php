<?php 

namespace yii\helpers;
use Yii;
use yii\helpers\SoapClient;
use yii\helpers\Html;
use app\models\Bgysdiftalep;
use app\models\Bgysdiftakip;


class imdat
{

	public static function item_tipi($id)
	{
	  $tipler = array(1 =>"Rol" ,2=>"İzin");
	  return $tipler[$id];
	}

	public static function kursdurumu($id)
	{
	  $tipler = array(0 =>"Pasif" ,1=>"Aktif");
	  return @$tipler[$id];
	}

	public static function kayittankursdurumu($id)
	{
	  	$durumu = Yii::$app->db->createCommand('SELECT durumu FROM basvuru_kurslar where id='.$id)->queryAll();	
	  	if (count($durumu)!=0) {
	  		//var_dump($durumu[0]['durumu']);exit;
	  		$durumu=$durumu[0]['durumu'];
	  		$tipler = array(0 =>"Pasif" ,1=>"Aktif");

	  		return @$tipler[$durumu];
		}else
			return "Pasif";
	}
	
	public static function onaydurumu($id)
	{
	  $tipler = array(0 =>"Onaysız" ,1=>"Onaylı");
	  return @$tipler[$id];
	}

	
	public static function tomysqldate($date)
	{
		
		if ($date) {
			$tarih=explode("/",$date);
        	$tarih=$tarih[2]."-".$tarih[1]."-".$tarih[0];
		}else{
			$tarih=null;
		}
		return $tarih;
	}

	public static function mysqltowebdate($date)
	{
		$tarih=explode("-",$date);
        $tarih=$tarih[2]."/".$tarih[1]."/".$tarih[0];

		return $tarih;
	}

	public function kayitbenimmi($user,$id,$table)
	{
		$kayitkontrol = Yii::$app->db->createCommand('SELECT id FROM '.$table.' where userid='.$user.' and id='.$id)->queryAll();
		//var_dump(count($kayitkontrol));exit;
		if (count($kayitkontrol)!=0) {
			return 1;
		}else{
			return 0;
		}
	
	}

	public function userbilgibenimmi($id)
	{
		$kayitkontrol = Yii::$app->db->createCommand('SELECT id FROM user_bilgi where kisi_id='.$id)->queryone();
		//var_dump(($kayitkontrol));exit;
		if ($kayitkontrol) {
			return 1;
		}else{
			return 0;
		}
	
	}

	public static function difform($id)
	{
		$difform=Bgysdiftakip::findOne(['diftalep_id'=>$id]);
		if (($difform)!=null) {
			return 1;
		}else{
			return 0;
		}
	}

	public static function difformonaydurumu($id)
	{
		$difform=Bgysdiftakip::findOne(['diftalep_id'=>$id]);
		//echo "<pre>";var_dump(($difform));Exit;
		if (($difform)!=null ) {
			if ($difform->onay) {
				return 1;	
			}else
				return 0;
		}else{
			return 0;
		}
	}

    public function kpstcdogrulama($tc,$ad,$soyad,$dogumyili) {
        $tcdogrulama = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL", array('soap_version' => SOAP_1_2));
        $tcdogrulama = $tcdogrulama->TCKimlikNoDogrula( array('TCKimlikNo' => $tc, 'Ad' => $ad, 'Soyad' => $soyad, 'DogumYili' => $dogumyili));
       
        return $tcdogrulama->TCKimlikNoDogrulaResult;
    }

    public function renkler($id) {
    	$renk=['#f44141','#414ff4','#4341f4','#6441f4','#472222','#474322','#3a4722','#244722','#224742','#223247','#332247','#472238','#876d6d','#87826d','#78876d',
    	'#6d877d','#6d7087','#806d87','#876d74','#af2626','#af6f26','#af9526','#4caf26','#26af43','#26af81','#269caf','#2665af','#2631af','#4826af','#7c26af',
    	'#af2683','#af263c'];
    	/*	$renk=[	'#ffb3b3','#ff3333','#e60000','#800000','#ffeb99','#ffdb4d','#e6b800','#806600','#88cc00','#446600','#9fdf9f','#40bf40','#194d19',
     			'#ffe699','#e6ac00','#664d00','#80d4ff','#00aaff','#004466','#c299ff','#6600ff','#330080']	; */
     	return $renk[$id];
    }

	public function str_split_unicode($str, $length = 1) {
		    $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
		    if ($length > 1) {
		        $chunks = array_chunk($tmp, $length);
		        foreach ($chunks as $i => $chunk) {
		            $chunks[$i] = join('', (array) $chunk);
		        }
		        $tmp = $chunks;
		    }
		    return $tmp;
		}

    public function buyukharf($string){
    	$kucukharf=" abcçdefgğhıijklmnoöprsştuüvyzwq";
    	$buyukharf=" ABCÇDEFGĞHIİJKLMNOÖPRSŞTUÜVYZWQ";
    	
    	$string = imdat::str_split_unicode($string);
    	$kucukharf = imdat::str_split_unicode($kucukharf);
    	$buyukharf = imdat::str_split_unicode($buyukharf);

    	foreach ($string as $key => $value) {
    		$indeks=array_search($value, $kucukharf);
    		$buyukkelime[$key]=$buyukharf[$indeks];
    	}
    	//var_dump($buyukkelime);

    	return join("",$buyukkelime);
    }

}

?>