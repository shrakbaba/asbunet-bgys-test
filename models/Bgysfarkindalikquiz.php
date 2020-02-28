<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bgys_farkindalik_quiz".
 *
 * @property int $id
 * @property string $cevaplayan
 * @property string $ip
 * @property string $cevaplamatarihi
 * @property string $cevaplar
 */
class Bgysfarkindalikquiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bgys_farkindalik_quiz';
    }
    /*
    public $dogrular=[
    'soru1'=>'c) Yazıcıya evrakları sabah gönderip öğlen almak',
    'soru2'=>'a) Herkes',
    'soru3'=>'d) 74Qa4.5r',
    'soru4'=>'d) Kaynağı belli olmayan e-postaları kontrol edip gerekirse içindeki linklere tıklayıp sonra Bilgi İşleme haber verilmeli.',
    'soru5'=>'d) Bilgisayar performansını artırarak çalışma ortamını iyileştirebilir.',
    'soru6'=>'b) Telefon veya mail yoluyla şifre istenmesi durumunda bilgi işlem dışında kimseye şifre verilmemelidir.'    ,
    'soru7'=>'b) Çok yakın ve güvendiğiniz iş arkadaşlarınız dışında kimsenin bilgisayarınızda çalışmamasına özen göstermeniz.'    ,
    'soru8'=>'b) Çeşitlilik'   ,
    'soru9'=>'a) Her ihtimale karşılık bilgisayarınıza haftada bir format atınız.'    ,
    'soru10'=>'d) Bilgisayarımız kasmasın diye antivirüs yüklememeliyiz.'   ,
    'soru11'=>'c) Şifreler sadece iş arkadaşlar ile paylaşılmalıdır.']   ;
    */

public $dogrular=[
    'soru1'=>2,
    'soru2'=>0,
    'soru3'=>3,
    'soru4'=>3,
    'soru5'=>3,
    'soru6'=>1,
    'soru7'=>1,
    'soru8'=>1,
    'soru9'=>0,
    'soru10'=>3,
    'soru11'=>2]   ;

public $soru1,$soru2,$soru3,$soru4,$soru5,$soru6,$soru7,$soru8,$soru9,$soru10,$soru11,$soru12,$soru13;
public $soru1data = [
    0 => 'a) Bilgisayarın ekranını kilitleyip gitmek', 
    1 => 'b) Bilgisayarda önemli doküman bulundurmamak', 
    2 => 'c) Yazıcıya evrakları sabah gönderip öğlen almak', 
    3 => 'd) Arşiv odalarını kilitli bırakmak']; 
public $soru2data = [
    0 => 'a) Herkes', 
    1 => 'b) Bilgi işlem Departmanı', 
    2 => 'c) Genel Müdür', 
    3 => 'd) Müşteri'];
public $soru3data = [
    0 => 'a) 123456a', 
    1 => 'b) Asdf1234', 
    2 => 'c) şifre123', 
    3 => 'd) 74Qa4.5r'];
public $soru4data = [
    0 => 'a) Güvenilmeyen eklentiler açılmamalıdır.', 
    1 => 'b) Spam e-postalara cevap verilmemeli, yönlendirilmemelidir.', 
    2 => 'c) E-posta adres bilgisi güvenilir kaynaklara verilmelidir.', 
    3 => 'd) Kaynağı belli olmayan e-postaları kontrol edip gerekirse içindeki linklere tıklayıp sonra Bilgi İşleme haber verilmeli.'];
public $soru5data = [
    0 => 'a) Bilgisayarları bilişim suçlarına dâhil edebilir.', 
    1 => 'b) İşletim sisteminizin veya diğer programlarınızın çalışmamasına, hatalı çalışmasına neden olabilirler.', 
    2 => 'c) Bilgisayarınızdaki dosya / klasörleri silebilir, kopyalayabilir, yerlerini değiştirebilir veya yeni dosyalar ekleyebilirler.', 
    3 => 'd) Bilgisayar performansını artırarak çalışma ortamını iyileştirebilir.'];
public $soru6data = [
    0 => 'a) Taşıdığınız, işlediğiniz verilerin öneminin bilincinde olunmalıdır.', 
    1 => 'b) Telefon veya mail yoluyla şifre istenmesi durumunda bilgi işlem dışında kimseye şifre verilmemelidir.', 
    2 => 'c) Arkadaşlarınızla paylaştığınız bilgileri seçerken dikkat edilmelidir.', 
    3 => 'd) Özellikle telefonda, e-posta veya sohbet yoluyla yapılan haberleşmelerde şifre gibi özel bilgiler kimseye söylenmemelidir.'];
public $soru7data = [
    0 => 'a) Şifrenizi izin veya herhangi bir gerekçe ile kimse ile paylaşmayınız.', 
    1 => 'b) Çok yakın ve güvendiğiniz iş arkadaşlarınız dışında kimsenin bilgisayarınızda çalışmamasına özen göstermeniz.', 
    2 => 'c) Şifrenizde rakam ve özel karakterler kullanınız.', 
    3 => 'd) Güvenli olmadığını düşündüğünüz mekânlarda kurumsal şifrenizi kullanmayınız.'];
public $soru8data = [
    0 => 'a) Bütünlük', 
    1 => 'b) Çeşitlilik', 
    2 => 'c) Gizlilik', 
    3 => 'd) Erişilebilirlik'];
public $soru9data = [
    0 => 'a) Her ihtimale karşılık bilgisayarınıza haftada bir format atınız.', 
    1 => 'b) Bilgisayarınıza güçlü bir güvenlik yazılımı yükleyin ', 
    2 => 'c) Kimden geldiğini bilmediğiniz e-postaları açmayın ', 
    3 => 'd) Bilmediğiniz programları bilgisayarınıza yüklemeyin, çalıştırmayın'];
public $soru10data = [
    0 => 'a) İşletim sistemine ait güncelemeleri yapmalıyız ', 
    1 => 'b) Kimden geldiğini bilmediğimiz e-postaları açmamalıyız.', 
    2 => 'c) Antivirüsümüzü güncel tutmalıyız. ', 
    3 => 'd) Bilgisayarımız kasmasın diye antivirüs yüklememeliyiz.'];
public $soru11data = [
    0 => 'a) Şifreler minimum 8 karakterden oluşmalıdır', 
    1 => 'b) PC başından ayrılırken PC mutlaka kilitlenmelidir', 
    2 => 'c) Şifreler sadece iş arkadaşlar ile paylaşılmalıdır.', 
    3 => 'd) Kendi ismimizi şifre olarak vermemeliyiz.'];

/*
public $soru2data=[1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"];
public $soru3data=['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C'];
public $soru4data = [0 => 'Morning', 1 => 'Noon', 2 => 'Evening']; 
*/
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cevaplayan', 'ip', 'cevaplar'], 'required'],
            [['cevaplamatarihi'], 'safe'],
            [['cevaplayan', 'ip', 'puan'], 'string', 'max' => 255],
            [['cevaplar'], 'string', 'max' => 750],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cevaplayan' => 'Ad Soyad',
            'ip' => 'IP',
            'cevaplamatarihi' => 'Cevaplama Tarihi',
            'cevaplar' => 'Cevaplar',
            'soru1'=>'1.    Aşağıdakilerden hangisi bilinçsiz kullanıma örnektir?',
            'soru2'=>'2.    Bilgi Güvenliğinden kim sorumludur?',
            'soru3'=>'3.    Aşağıdakilerden hangisi kötü şifre örneklerinden biri değildir?',
            'soru4'=>'4.    Aşağıdakilerden hangisi E- posta güvenliği için yapılmamalıdır? ',
            'soru5'=>'5.    Aşağıdakilerden hangisi zararlı programların yapabileceklerinden değildir?',
            'soru6'=>'6.    Aşağıdakilerden hangisi doğru değildir?',
            'soru7'=>'7.    Aşağıdakilerden hangisi bilgi güvenliği için yapılması gerekenlerden biri değildir? ',
            'soru8'=>'8.    Aşağıdakilerden hangisi bilgi güvenliğinin temel öğesi değildir?',
            'soru9'=>'9.    Zararlı yazılımlardan korunmak için aşağıdakilerden hangisini yapmamız önerilmez?',
            'soru10'=>'10.   Zararlı yazılımlardan korunmak için aşağıdakilerden hangisini yapmamız uygun değildir?',
            'soru11'=>'11.   Şifre kullanımı ile ilgili bilgilerden hangisi yanlıştır?',
            'puan'=> 'Sınav Sonucu',
        ];
    }
}
