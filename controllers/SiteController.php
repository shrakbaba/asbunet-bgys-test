<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


use yii\helpers\imdat;
use yii\helpers\bgys;
use yii\helpers\snmp;
use yii\web\UploadedFile;
use SoapClient;

use app\models\Bgysrisk;
use app\models\Bgysriskkabul;
use app\models\Bgysvarlikenvanteri;
use app\models\Bgysolaykayit;
use app\models\Bgysdiftalep;
use app\models\Bgysdiftakip;
use app\models\Envcihazliste;
use app\models\Bgysfirmabilgi;
use app\models\Bgysfirmadegerlendirme;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','login','contact','about','uzaklogin','snmp'],
                        'roles' => [],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['kpstcdogrula','mesajat'],
                        'roles' => ['BGYS_Yonetim_Temsilcisi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['dashboard'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                   /* [
                      'allow' => false,
                      'roles' => ['@'],
                      'denyCallback' => function($rule, $action) {
                         Yii::$app->session->setFlash('error', 'Hatalı işlem.');
                         Yii::$app->user->loginRequired();
                       }
                    ]*/
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSnmp()
    {
        //$sysdescr = @snmpget("10.0.199.1", "asbu*2013", '.1.3.6.1.2.1.43.11.1.1.9.1.1');
        //$sysdescr = snmpget("10.0.199.1", "asbu*2013",300);
       // print_r($sysdescr);
        $snmp = new snmp('read');
        $getActive = $snmp->getAllPortsStatus(); 
        print_r($getActive);   
    }

    public function actionUzaklogin()
    {

        if(Yii::$app->request->post()){
            return 1;
        }

        /*
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) ) {

            if ($model->login()) {
                //echo ";adsda";exit;
                //bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'giris yapti','' );
                //return $this->goBack();
                if (Yii::$app->user->can('BGYS_Ekip_Uyesi')) {
                    return 1;                
                }else{
                    return 0;
                }
            }  
        }
        return $this->render('login', [
            'model' => $model,
        ]);*/
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) ) {

            if ($model->login()) {
                //echo ";adsda";exit;
                //echo "asdsadwqeqead234as";exit;
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'giris yapti','' );
                if (Yii::$app->user->can('BGYS_Ekip_Uyesi') ) {
                    //echo "yetkivar";exit;
                    return $this->redirect('dashboard');                
                }else{
                    return $this->redirect('/site/index');
                }
            }  
        }
        return $this->render('login', [
            'model' => $model,
        ]);
        
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
           return $this->goHome();
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->login()) {
                //echo ";adsda";exit;
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'giris yapti','' );
                //return $this->goBack();
                if (Yii::$app->user->can('BGYS_Ekip_Uyesi')) {
                    return $this->redirect('dashboard');                
                }else{
                    return $this->redirect('/site/index');
                }
            }  
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cikis yapti','' );
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    { 
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()   //kpssorgusu
    {
        return $this->render('about');
    }
  
    public function actionDashboard(){

        $risk= Bgysrisk::find()->count();
        $riskkabul= Bgysriskkabul::find()->count();

        $varlik= Bgysvarlikenvanteri::find()->count();   
        $varlikkategori = (new \yii\db\Query())
            ->select(['count(l.id) as value','t.adi'])
            ->from('bgys_varlik_envanteri l')
            ->leftJoin('bgys_kategori t', 't.id=l.kategori')
            ->groupBy(['l.kategori'])
            ->limit(3)
            ->all();   
        $varlikkritik= Bgysvarlikenvanteri::find()->where('varlik_degeri=4')->count(); 
        
        $diftalep= Bgysdiftalep::find()->count(); 
        $diftalepkapali= Bgysdiftalep::find()->where('durum=1')->count();   
        
        $diftakip= Bgysdiftakip::find()->count();        
        $diftakiponayli= Bgysdiftakip::find()->where('onay=1')->count();      
        
        $cihazliste= Envcihazliste::find()->count();   

        $cihazturu = (new \yii\db\Query())
            ->select(['count(l.id) as value','t.cihaz_turu'])
            ->from('env_cihaz_liste l')
            ->leftJoin('env_cihaz_turu t', 't.id=l.cihaz_turu_id')
            ->groupBy(['l.cihaz_turu_id'])
            ->limit(3)
            ->all();

        $degerlendirme= Bgysfirmadegerlendirme::find()->count();        
        $degerlendirmeonayli= Bgysfirmadegerlendirme::find()->where('onay=1')->count();

        $turler = (new \yii\db\Query())
            ->select(['sum(adet) as value', 't.cihaz_turu as label'])
            ->from('env_cihaz_liste l')
            ->leftJoin('env_cihaz_turu t', 't.id=l.cihaz_turu_id')
            ->groupBy(['l.cihaz_turu_id'])
            ->all();
           
         foreach ($turler as $key => $value) {
            $color = '#'.dechex(rand(0x000000, 0xFFFFFF));
            $turler2[$key]['value']=$value['value'];
            $turler2[$key]['label']=$value['label'];
            $turler2[$key]['color']=$color;
            $turler2[$key]['highlight']=$color;
         }
        $PieDataTur   =@json_encode($turler2);

        $markalar = (new \yii\db\Query())
            ->select(['sum(adet) as value', 'm.marka as label'])
            ->from('env_cihaz_liste l')
            ->leftJoin('env_marka m', 'm.id=l.marka_id')
            ->groupBy(['l.marka_id'])
            ->all(); 
         foreach ($markalar as $key => $value) {
            $color = '#'.dechex(rand(0x000000, 0xFFFFFF));
            $markalar2[$key]['value']=$value['value'];
            $markalar2[$key]['label']=$value['label'];
            $markalar2[$key]['color']=$color;
            $markalar2[$key]['highlight']=$color;
         }
        $PieDataMarka   =@json_encode($markalar2);

       // var_dump($PieDataTur);echo "<br>//////////////////////";var_dump($PieDataMarka);exit;
        $olaykayit= Bgysolaykayit::find()->count();
        $yeniolaykayit= Bgysolaykayit::find()->where(['between', 'olaytarihi', date('Y-m-d',strtotime("-1 month")), date('Y-m-d') ])->count();

        //echo "<pre>";var_dump($olaykayit);echo "<br>###############";
        //echo "<pre>";var_dump($yeniolaykayit);exit;

        $sql = 'SELECT firmaadi,id FROM bgys_firma_bilgi  WHERE id IN (SELECT Min(id) FROM bgys_firma_bilgi GROUP BY firmaadi)';
        $tedarikciler= Bgysfirmabilgi::findBySql($sql)->all();
        
        $sql = 'SELECT firmaid FROM bgys_firma_degerlendirme GROUP BY firmaid';
        $degerlendirmeler= Bgysfirmadegerlendirme::findBySql($sql)->all();

        $b=null; $a=null; $xx=null;
        foreach ($tedarikciler as $key => $value) {
            $a[$key]=$value->id;       //firma id leri
        }
        foreach ($degerlendirmeler as $key => $value) {
            $b[$key]=$value->firmaid;   //degerlendirilen firma idleri
        }
        if ($a and $b ) {
            $degerlendirilmeyenler=array_diff($a,$b);

            foreach ($degerlendirilmeyenler as $key => $value) {
                $c =Bgysfirmabilgi::find()->where('id='.$value)->one();
                //echo "<pre>";var_dump($c->firmaadi);exit;
                $xx[$key] = $c->firmaadi;
            }
            //echo "<pre>";var_dump($xx);exit;
        }
        
        $sql = 'SELECT firmaadi,id FROM bgys_firma_bilgi  WHERE id IN (SELECT Min(id) FROM bgys_firma_bilgi GROUP BY firmaadi)';
        $tedarikci= Bgysfirmabilgi::findBySql($sql)->count();
        $tedarikcipersonel= Bgysfirmabilgi::find()->count();

        $tedarikcitipi = (new \yii\db\Query())
            ->select(['count(l.id) as value',new \yii\db\Expression('CASE l.tedarik_tipi 
                WHEN 1 THEN "Hizmet" WHEN 2 THEN "Malzeme" WHEN 3 THEN "Servis" WHEN 4 THEN "Yüksek Teknoloji" ELSE "" END as tip')])
            ->from('bgys_firma_bilgi l')
            ->groupBy(['l.tedarik_tipi'])
            ->limit(3)
            ->all();   
            //echo "<pre>";var_dump($tedarikcitipi) ;exit; 
            //1 =>"Hizmet" ,2=>"Malzeme",3=>'Servis',4=>'Yüksek Teknoloji'


        $riskdetay = Bgysrisk::find()->all() ; //basvuru sonucu verilmisse
            $dusukrisk=[];
            $ortarisk=[];
            $yuksekrisk=[];
        if ($riskdetay) {
            foreach ($riskdetay as $key => $value) {
                if ($value['riskdegeri_onceki']<34) {
                    array_push($dusukrisk, $value);
                }elseif ($value['riskdegeri_onceki']<68) {
                    array_push($ortarisk, $value);
                }elseif ($value['riskdegeri_onceki']<101) {
                    array_push($yuksekrisk, $value);
                }
            }
        }
        if (count($dusukrisk)!=0 and count($dusukrisk)!=null ) {
            $riskdata[0]['0']='Düşük Risk';
            $riskdata[0]['1']=count($dusukrisk);
        }
        if (count($ortarisk)!=0 and count($ortarisk)!=null) {
            $riskdata[1]['0']='Orta Risk';
            $riskdata[1]['1']=count($ortarisk);
        }
        if (count($yuksekrisk)!=0 and count($yuksekrisk)!=null) {
            $riskdata[2]['0']='Yüksek Risk';
            $riskdata[2]['1']=count($yuksekrisk);
        }
        $basvurusonuclari=@$riskdata;
        if ($basvurusonuclari!=null) {  
            $basvurusonuclari=(array_values(array_filter($basvurusonuclari)));
        }
        $riskdegisim = (new \yii\db\Query())
            ->select(['count(l.id) as value',new \yii\db\Expression('CASE l.ozetdurum 
                WHEN 1 THEN "Risk Azalmış" WHEN 2 THEN "Risk Artmış" WHEN 4 THEN "Risk Kabul" ELSE "Değişim Yok" END as ozetdurum')])
            ->from('bgys_risk l')
            ->groupBy(['l.ozetdurum'])
            ->all();
        if ($riskdegisim) {
            foreach ($riskdegisim as $key => $value) {
                $sonuclar[$key]['0']=($value['ozetdurum']);
                $sonuclar[$key]['1']=intval($value['value']);
            }
            $riskdegisim=$sonuclar; 
        }else
            $riskdegisim=null; 


        $riskhatirasi=Bgysrisk::find()->all();

        if ($riskhatirasi) {
            $arr=[[]];
            foreach ($riskhatirasi as $key => $value) {
                $arr[$key]=[
                    'varlik'=>$value->varlik,
                    'deger'=>$value->riskdegeri_onceki,
                    'name'=>$value->risk,
                    'olasilik'=>$value->olasilik_onceki,
                    'gbe'=>max($value->gizlilik_onceki,$value->butunluk_onceki,$value->erisilebilirlik_onceki),
                    'varlikdegeri'=>Bgysvarlikenvanteri::find()->where(['id'=>($value->varlik)])->one()->varlik_degeri,
                ];
            }
            $byGroup = group_by("varlik", $arr);      

            /*
            $arr = [
                [ 
                "name" => "Bilgisyar" , 
                "data" => [
                           [ 'name' => 'Bilgisayarlara', "value" => 16 ],
                           [ 'name' => 'erererer', "value" => 28 ],
                           [ 'name' => 'erererer', "value" => 28 ],
                        ] 
                ],
                [ 
                "name" => "Ağ" , 
                "data" => [
                       [ 'name' => 'xxx', "value" => 36 ],
                       [ 'name' => 'yyy', "value" => 12 ]
                    ], 
                ]
            ];
            */

            $c=array();
            $b=array();
            foreach ($byGroup as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $c[$key2]= ['name'=>$value2['name'],'value'=>$value2['deger'],'olasilik'=>$value2['olasilik'],'gbe'=>$value2['gbe'],'varlik'=>$value2['varlikdegeri']];
                }
                $b[]= ['name'=>@(Bgysvarlikenvanteri::find()->where(['id'=>($key)])->one()->varlik_adi),'data'=>$c];
            }
        }



        $data=['risk'=>@$risk,'riskkabul'=>@$riskkabul,'varlik'=>@$varlik,'varlikkritik'=>@$varlikkritik,'diftalep'=>@$diftalep,'diftalepkapali'=>@$diftalepkapali,'diftakip'=>@$diftakip,'diftakiponayli'=>@$diftakiponayli,'cihazliste'=>@$cihazliste,'tedarikci'=>@$tedarikci,'tedarikci'=>@$tedarikci,'tedarikcipersonel'=>@$tedarikcipersonel,'degerlendirme'=>@$degerlendirme,'degerlendirmeonayli'=>@$degerlendirmeonayli,'degerlendirilmeyenler'=>@$xx,'PieDataTur'=>$PieDataTur,'PieDataMarka'=>$PieDataMarka,'olaykayit'=>$olaykayit,'yeniolaykayit'=>$yeniolaykayit,'basvurusonuclari'=>$basvurusonuclari,'riskdegisim'=>$riskdegisim,'varlikkategori'=>$varlikkategori,'cihazturu'=>$cihazturu,'tedarikcitipi'=>$tedarikcitipi,'riskhatirasi'=>$b];

        return $this->render('dashboard',['data'=>$data]);
    }


 }

function group_by($key, $array) {
    $result = array();

    foreach($array as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}