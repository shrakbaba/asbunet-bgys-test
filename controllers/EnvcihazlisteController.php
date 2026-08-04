<?php

namespace app\controllers;

use Yii;
use app\models\Envcihazliste;
use app\models\EnvcihazlisteSearch;
use app\models\Authassignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use yii\helpers\imdat;
use yii\web\UploadedFile;
use yii\helpers\bgys;
/**
 * EnvcihazlisteController implements the CRUD actions for Envcihazliste model.
 */
class EnvcihazlisteController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'pdfsil' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'user',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['mailat'],
                        'roles' => [],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','view','dashboard'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete','pdfsil','zimmet'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                      'allow' => false,
                      'roles' => ['@'],
                      'denyCallback' => function($rule, $action) {
                         Yii::$app->session->setFlash('error', 'Hata oluştu.');
                         Yii::$app->user->loginRequired();
                       }
                    ]
                ],
            ],
        ];
    }

    public function actionMailat()  //bakım kayıtlarının hatırlatması için crobtab ile çağırılacak
    {
        bgys::cronErisiminiDogrula();

        $cihazlar=Envcihazliste::find()->all();
        if (count($cihazlar)!=0) {
            $birayliklar=[];
            $ucayliklar=[];
            $altiayliklar=[];
            $mailler=[];
            $maillistesi=[];

            foreach ($cihazlar as $key => $value) {
                $biraykaldi =date('Y-m-d',strtotime("-1 days",strtotime("-1 months", strtotime($value->garanti_bitis))));
                $ucaykaldi  =date('Y-m-d',strtotime("-1 days",strtotime("-3 months", strtotime($value->garanti_bitis))));
                $altiaykaldi=date('Y-m-d',strtotime("-1 days",strtotime("-6 months", strtotime($value->garanti_bitis)))); 

                $b = [$value->cihazTuru->cihaz_turu, $value->marka->marka, $value->model->model, $value->alim_tarihi, $value->garanti_bitis, $value->key];
                
                if ($value->zimmet) {
                    $zimmetEmail = bgys::zimmetEmail($value->zimmet);
                    if ($zimmetEmail) {
                        array_push($maillistesi, $zimmetEmail);
                    }
                }

                if (date('Y-m-d')==$altiaykaldi)   {   $a=[6]; /*array_push($birayliklar,$a);   */    } 
                elseif (date('Y-m-d')==$ucaykaldi) {   $a=[3]; /*array_push($ucayliklar,$a);    */    } 
                elseif (date('Y-m-d')==$biraykaldi){   $a=[1]; /*array_push($altiayliklar,$a);  */    }
                else                               {   $a=[0]; /*array_push($altiayliklar,$a);  */    }
                array_push($a,$b);
                if ($a[0]!=0) {             
                    array_push($mailler,$a);
                }
                //echo "<pre>";var_dump($a);exit;
            }
        }
        $maillistesi = bgys::mailListesiOlustur('cihazGaranti', $maillistesi);

        /*$yonetimtemsilcisi=Authassignment::find()->where(['item_name'=>'BGYS_Yonetim_Temsilcisi'])->all();
        if ($yonetimtemsilcisi) {
            foreach ($yonetimtemsilcisi as $key2 => $value2) {                      
            $ldapObject = @\Yii::$app->ad->search()->findBy('sAMAccountname', @$value2->user->username)->mail[0];
                array_push($maillistesi,$ldapObject);
            }
        }*/
        usort($mailler, function($a, $b) { return $a[0] <=> $b[0];   });  //çift katlı array i index e göre sıralama
        if (count($mailler) and count($maillistesi))
        {
            bgys::garantibildir($mailler, $maillistesi);
        }
        
        //fopen('/var/www/html/bgys/web/uploads/denemeeme.txt', 'w');           
        //return 1;
    }

    public function actionDashboard()
    {
        $turler2 = [];
        $markalar2 = [];
        $modeller2 = [];
        $markalardrill = [];
        $modellerdrill = [];
        $modellerdrill2 = [];

        $turler = (new \yii\db\Query())
        ->select(['sum(adet) as adet', 't.cihaz_turu'])
        ->from('env_cihaz_liste l')
        ->leftJoin('env_cihaz_turu t', 't.id=l.cihaz_turu_id')
        ->groupBy(['l.cihaz_turu_id'])
        ->all();
        
        foreach ($turler as $key => $value) {
            $turler2[$key]['0']=($value['cihaz_turu']);
            $turler2[$key]['1']=intval($value['adet']);
        }
        foreach ($turler as $key => $value) {
            $turlerdrill[$key]['name']=($value['cihaz_turu']);
            $turlerdrill[$key]['y']=intval($value['adet']);
            $turlerdrill[$key]['drilldown']=($value['cihaz_turu']);
        }

        $markalar = (new \yii\db\Query())
        ->select(['sum(adet) as adet', 'm.marka'])
        ->from('env_cihaz_liste l')
        ->leftJoin('env_marka m', 'm.id=l.marka_id')
        ->groupBy(['l.marka_id'])
        ->all();  //markalara göre adetler
        // echo "<pre>";var_dump($turler);exit;
            foreach ($markalar as $key => $value) {  //markalar arrayını 0 1 indekse çevir
                $markalar2[$key]['0']=($value['marka']);
                $markalar2[$key]['1']=intval($value['adet']);
            }
            foreach ($markalar as $key => $value) { //markalar arrayini drille çevir
                $markalardrill[$key]['name']=($value['marka']);
                $markalardrill[$key]['y']=intval($value['adet']);
                $markalardrill[$key]['drilldown']=($value['marka']);
            }

            foreach ($markalardrill as $key => $value) { 
                $marka=$value['name'];
                $modellerdrill[$key] = (new \yii\db\Query())
                ->select(['sum(l.adet) as adet', 'm.model','a.marka' ])
                ->from('env_cihaz_liste l')
                ->leftJoin('env_model m', 'm.id=l.model_id')
                ->leftJoin('env_marka a', 'a.id=m.marka_id')
                ->where(['a.marka' => $marka])
                ->groupBy(['l.model_id'])
                    ->all(); // markanın modellerinin urun adetleri drille gore     
                }

                foreach ($modellerdrill as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $modellerdrill2[$key]["data"][$key2]['0']=$value2['model'];
                        $modellerdrill2[$key]["data"][$key2]['1']=intval($value2['adet']);
                        $modellerdrill2[$key]["name"] = $value2['marka'];
                        $modellerdrill2[$key]["id"] = $value2['marka'];  
                    }

                }    
                $modeller = (new \yii\db\Query())
                ->select(['sum(l.adet) as adet', 'm.model'])
                ->from('env_cihaz_liste l')
                ->leftJoin('env_model m', 'm.id=l.model_id')
                ->groupBy(['l.model_id'])
                ->all();         
                foreach ($modeller as $key => $value) {
                    $modeller2[$key]['0']=($value['model']);
                    $modeller2[$key]['1']=intval($value['adet']);
                }

                return $this->render('dashboard',['tur'=>$turler2,'marka'=>$markalar2,'model'=>$modeller2,'markalardrill'=>$markalardrill,'modellerdrilldown'=>$modellerdrill2]);
    }

    public function actionIndex()
    {
            $searchModel = new EnvcihazlisteSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        
    }

    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionZimmet($id)
    {
        return $this->render('zimmet', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Envcihazliste();
        
        //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';

        if ($model->load(Yii::$app->request->post())) 
            {
                $model->alim_tarihi=imdat::tomysqldate($model->alim_tarihi);
                $model->garanti_bitis=imdat::tomysqldate($model->garanti_bitis);
                //echo $model->alim_tarihi;echo "<br>";echo $model->garanti_bitis;exit;

                $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->dosya = Yii::$app->security->generateRandomString().".{$ext}";
                    //$path = Yii::getAlias('@env_dosya') ."/".$model->dosya;
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("cihaz")."/".$model->dosya;

                    if ($model->validate()) {                
                            
                        if ($model->save()) {
                            $model->file->saveAs($path);
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz tanımlandı','cihaz:'.$model->id );
                            //return $this->redirect(['view', 'id' => $model->id]);                            
                            //return $this->redirect(['index']);
                            return $this->redirect(Yii::$app->request->referrer);
                        }else{
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save()) { 
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                } 
                
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $model->alim_tarihi=imdat::mysqltowebdate($model->alim_tarihi);
        $model->garanti_bitis=imdat::mysqltowebdate($model->garanti_bitis); 

        if ($model->load(Yii::$app->request->post()) )
            {
                $model->alim_tarihi=imdat::tomysqldate($model->alim_tarihi);
                $model->garanti_bitis=imdat::tomysqldate($model->garanti_bitis);

                $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->dosya = Yii::$app->security->generateRandomString().".{$ext}";
                    //$path = Yii::getAlias('@env_dosya') ."/".$model->dosya;
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("cihaz")."/".$model->dosya;

                    if ($model->validate()) {                
                            
                        if ($model->save()) {
                            $model->file->saveAs($path);
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz guncellendi','cihaz:'.$model->id );
                            //return $this->redirect(['view', 'id' => $model->id]);
                            return $this->redirect(Yii::$app->request->referrer);
                        }else{
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save()) { 
                            return $this->redirect(Yii::$app->request->referrer);
                    }
                } 

                         

            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);

        }

    
    public function actionDelete($id)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        //echo "<pre>";var_dump($this->findModel($id)->belge);exit;
        $yol=Yii::$app->basePath .'/web/uploads/bgys/'.md5("cihaz")."/". $this->findModel($id)->dosya;
        //var_dump($yol);exit;
        try {
            //echo "<pre>";var_dump($this->findModel($id));exit;
            if ($this->findModel($id)->dosya and file_exists($yol)) {
                //echo 1;exit;
                unlink($yol);
            }
            //echo 2;exit;
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz silindi','cihaz:'.$id );
            $this->findModel($id)->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success','Silme işlemi başarılı.');
            return $this->redirect(['index']);

        } catch (IntegrityException $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error','Silme işleminde sorun oluştu.');
            return $this->redirect(['index']);

        }catch (\Exception $e) {

            $transaction->rollBack();
            Yii::$app->session->setFlash('error','Silme işleminde hata oluştu.');

           // echo "<pre>";var_dump($e->errors);exit;
            return $this->redirect(['index']);
        }
    }

    public function actionPdfsil($i=null)
    { 
        if ($i) {
                //echo "<pre>";var_dump($this->findModel($i)->belge);exit;
            if ($this->findModel($i)->dosya) {

                $model =$this->findModel($i);
                $dosya=$model->dosya;
                //$model->photo==null;  
                        
            // echo "<pre>";var_dump($model->validate());
            // echo "<pre>";var_dump($model->getErrors());exit; 
                $a=Yii::$app->db->createCommand()
                ->update('env_cihaz_liste', ['dosya'=>null], 'id='.$i)
                ->execute();
                if ($a) {
                    unlink(Yii::$app->basePath .'/web/uploads/bgys/'.md5("cihaz")."/". $dosya);

                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz belgesi silindi','cihaz:'.$i);
                    Yii::$app->session->setFlash('success','Belge Silindi.');
                }else
                {
                    Yii::$app->session->setFlash('error','Hata');
                }
            }else{
                Yii::$app->session->setFlash('error','Belge bulunamadı.');
            }
            return $this->redirect('update?id='.$i);
        }else{
            Yii::$app->session->setFlash('error','Belge bulunamadı.');
            return $this->redirect("index");
        }
         
    }

    protected function findModel($id)
    {
        if (($model = Envcihazliste::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
