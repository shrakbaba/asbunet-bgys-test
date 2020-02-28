<?php

namespace app\controllers;

use Yii;
use app\models\Bgysolaykayit;
use app\models\BgysolaykayitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;

use yii\helpers\imdat;
use yii\helpers\bgys;
use app\models\Userdb;
use app\models\Userbilgi;
use yii\web\UploadedFile;


class BgysolaykayitController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete','pdfsil'],
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

    public function actionIndex()
    {
        $searchModel = new BgysolaykayitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysolaykayit();
        $a=Yii::$app->authManager->getUserIdsByRole("BGYS_Yonetim_Temsilcisi");
        if ($a) {
            foreach ($a as $key => $value) {
                if (Yii::$app->params['giristipi']==1) { //ad ile 
                    $b[$key]=\Edvlerblog\Adldap2\model\UserDbLdap::findOne($value)->username;
                    // $c[$key]=Userdb::findOne(['id'=>$value])->email;
                    $c[$key]=Userbilgi::findOne(['kisi_id'=>$value])->email;
                }else{
                    $b[$key]=Userdb::findOne($value)->username;
                    $c[$key]=Userdb::findOne(['id'=>$value])->email;
                }
            }
        }else{
            $b=null;
        }
        if ($model->load(Yii::$app->request->post()) ) {

            $model->olaytarihi=imdat::tomysqldate($model->olaytarihi);
            $model->mudahaletarihi=imdat::tomysqldate($model->mudahaletarihi);        
            $model->userid=Yii::$app->user->identity->id;

             $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->belge = Yii::$app->security->generateRandomString().".{$ext}";
                    //$path = Yii::getAlias('@env_dosya') ."/".$model->dosya;
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("olay")."/".$model->belge;

                    if ($model->validate()) {                
                            
                        if ($model->save() and (($b and $c) ? bgys::olaykayitbildirim($c,$model->konu,$model->sonuc) : 1==1) ) {
                            $model->file->saveAs($path);

                        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay tanımlandı','olay:'.$model->konu );
                            return $this->redirect(['view', 'id' => $model->id]);
                        }else{
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save() and (($b and $c) ? bgys::olaykayitbildirim($c,$model->konu,$model->sonuc) : 1==1) ) { 
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } 



          /*  if ($model->save() and (($b and $c) ? bgys::olaykayitbildirim($c,$model->konu,$model->sonuc) : 1==1) ){
                return $this->redirect(['view', 'id' => $model->id]);
            }*/
            
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $a=Yii::$app->authManager->getUserIdsByRole("BGYS_Yonetim_Temsilcisi");
        if ($a) {
            foreach ($a as $key => $value) {
              //$b[$key]=Userdb::findOne($value)->username;
              //$c[$key]=Userdb::findOne(['id'=>$value])->email;

              if (Yii::$app->params['giristipi']==1) { //ad ile 
                    $b[$key]=\Edvlerblog\Adldap2\model\UserDbLdap::findOne($value)->username;
                    // $c[$key]=Userdb::findOne(['id'=>$value])->email;
                    $c[$key]=Userbilgi::findOne(['kisi_id'=>$value])->email;
                }else{
                    $b[$key]=Userdb::findOne($value)->username;
                    $c[$key]=Userdb::findOne(['id'=>$value])->email;
                }
            }
        }else{
            $b=null;
        }

        $model->olaytarihi=imdat::mysqltowebdate($model->olaytarihi);
        $model->mudahaletarihi=imdat::mysqltowebdate($model->mudahaletarihi); 

        if ($model->load(Yii::$app->request->post())) {

            $model->userid=Yii::$app->user->identity->id;
            $model->olaytarihi=imdat::tomysqldate($model->olaytarihi);
            $model->mudahaletarihi=imdat::tomysqldate($model->mudahaletarihi);

            
            $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->belge = Yii::$app->security->generateRandomString().".{$ext}";
                    //$path = Yii::getAlias('@env_dosya') ."/".$model->dosya;
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("olay")."/".$model->belge;

                    if ($model->validate()) {                
                            
                        if ($model->save() and (($b and $c) ? bgys::olaykayitbildirim($c,$model->konu,$model->sonuc) : 1==1) ) {
                            $model->file->saveAs($path);

                        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay guncellendi','olay:'.$model->konu );
                            return $this->redirect(['view', 'id' => $model->id]);
                        }else{
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save() and (($b and $c) ? bgys::olaykayitbildirim($c,$model->konu,$model->sonuc) : 1==1) ) { 
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } 

            /*if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }*/
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        //echo "<pre>";var_dump($this->findModel($id)->belge);exit;
        $yol=Yii::$app->basePath .'/web/uploads/bgys/'.md5("olay")."/". $this->findModel($id)->belge;
        //var_dump($yol);exit;
        try {
            //echo "<pre>";var_dump($this->findModel($id));exit;
            if ($this->findModel($id)->belge and file_exists($yol)) {
                //echo 1;exit;
                unlink($yol);
            }
            //echo 2;exit;

                        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay silindi','olay:'.$this->findModel($id)->konu );
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
            if ($this->findModel($i)->belge) {

                $model =$this->findModel($i);
                $belge=$model->belge;
                //$model->photo==null;  
                        
            // echo "<pre>";var_dump($model->validate());
            // echo "<pre>";var_dump($model->getErrors());exit; 
                $a=Yii::$app->db->createCommand()
                ->update('bgys_olay_kayit', ['belge'=>null], 'id='.$i)
                ->execute();
                if ($a) {
                    unlink(Yii::$app->basePath .'/web/uploads/bgys/'.md5("olay")."/". $belge);
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay belgesi silindi','olay:'.$i);
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
        if (($model = Bgysolaykayit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
