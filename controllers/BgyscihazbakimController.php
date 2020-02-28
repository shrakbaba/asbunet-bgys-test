<?php

namespace app\controllers;

use Yii;
use app\models\Bgyscihazbakim;
use app\models\BgyscihazbakimSearch;
use app\models\Envcihazliste;
use app\models\Authassignment;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\imdat;
use yii\helpers\bgys;

/**
 * BgyscihazbakimController implements the CRUD actions for Bgyscihazbakim model.
 */
class BgyscihazbakimController extends Controller
{
    public function behaviors()
    {
        return [
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
                        'actions' => ['index','view',],
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

    public function actionMailat()  //bakım kayıtlarının hatırlatması için crobtab ile çağırılacak
    {
        $bakimlar=Bgyscihazbakim::find()->all();  //Tüm kayıtlar
        if ($bakimlar) {
            foreach ($bakimlar as $key => $value) {
               $cihaz= @Envcihazliste::find()->where(['id'=>$value->cihazid])->one();
               $marka=@$cihaz->marka->marka;
               $model=@$cihaz->model->model;
               $periyod=@$value->periyod;
               $bakimtarihi=@$value->bakimtarihi;
               //$email="alialiieren@gmail.com";
               $key=@$cihaz->key;
               $service_tag=@$cihaz->service_tag;
               // $zimmetemail=null;
                $maillistesi=[];
               if ($cihaz->zimmet) { //zimmet yapılmışsa
                    //$zimmetemail=$cihaz->zimmet0->email;
                    array_push($maillistesi,$cihaz->zimmet0->email);
               }

               $yonetimtemsilcisi=Authassignment::find()->where(['item_name'=>'BGYS_Yonetim_Temsilcisi'])->all();
               //$temsilciemailleri=[];
                if ($yonetimtemsilcisi) {
                    foreach ($yonetimtemsilcisi as $key2 => $value2) {
                        array_push($maillistesi,$value2->user->email);
                    }
                }
            
                for ($i=0; $i <3 ; $i++) { 
                    if ($periyod=="1ay")      {  $a=1;  }
                    elseif ($periyod=="3ay")  {  $a=3;  }
                    elseif ($periyod=="6ay")  {  $a=6;  }
                    elseif ($periyod=="12ay") {  $a=12; }
                    $y=($i+1)*$a;
                    $uyaritarihi=date('Y-m-d',strtotime("-7 days",strtotime("+$y months", strtotime($bakimtarihi))));
                    //bgys::bakima1hafta(imdat::mysqltowebdate($uyaritarihi), $marka, $model, $key, $service_tag, $maillistesi, imdat::mysqltowebdate($bakimtarihi));
                    //echo "<pre>";var_dump(date("Y-m-d"));echo "*--*";var_dump($uyaritarihi);exit;
                    if (date("Y-m-d")==$uyaritarihi) {
                        //1 hafta kaldı bakıma
                        bgys::bakima1hafta(imdat::mysqltowebdate($uyaritarihi), $marka, $model, $key, $service_tag, $maillistesi, imdat::mysqltowebdate($bakimtarihi));
                    }
                    // echo "<pre>";var_dump($ilkuyaritarihi);echo "<pre>";
                }
               
                //echo "<pre>";var_dump(date('Y-m-d'));

                //echo "<pre>";var_dump($value->periyod);exit;
            }
        }
    }
    /**
     * Lists all Bgyscihazbakim models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgyscihazbakimSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgyscihazbakim model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgyscihazbakim();

        if ($model->load(Yii::$app->request->post())) 
            {
                $model->kayittarihi=date('Y-m-d');
                
                $model->bakimtarihi=imdat::tomysqldate($model->bakimtarihi);

                if (UploadedFile::getInstance($model,'file')) {
                    $model->file =UploadedFile::getInstance($model,'file'); 
                    $ext = $model->file->extension;
                    $model->sozlesme = Yii::$app->security->generateRandomString().".{$ext}";
                    //echo dirname(dirname(__DIR__));exit;
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("bakim")."/".$model->sozlesme;
                    $model->file->saveAs($path);
                }
                $model->file=null;

                    //echo "2";


                $model->bakimformlari = UploadedFile::getInstances($model, 'bakimlar');

                if ($model->bakimformlari) {
                    $images = [];
                    $i=0;
                    foreach ($model->bakimformlari as $file) {

                        $ext = $file->extension;

                        $img_name = Yii::$app->getSecurity()->generateRandomString().".{$ext}";    

                        $path = Yii::getAlias('@env_dosya') ."bgys/".md5("bakim")."/".$img_name;    
                        $file->saveAs($path);   
                        

                        //array_push($images,$img_name);
                         $images[] = $img_name;
                        //$images[$i]= $img_name;
                        //$i++;
                    }

                    $images=json_encode($images);
                    $model->bakimformlari = $images;
                }else{
                    $model->bakimformlari = null;
                } 
                $model->bakimlar=null;


                if ($model->validate()) {     
                        //var_dump($model->errors);exit;           

                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                        return $this->redirect(['index']);
                    }
                }else{
                    Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                    return $this->redirect(['index']);
                }
            }                 
            

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }


    /**
     * Updates an existing Bgyscihazbakim model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $eskiformlar=json_decode($model->bakimformlari);
        //var_dump($eskiformlar);exit;
         $model->bakimtarihi = date("d/m/Y", strtotime($model->bakimtarihi));

        if ($model->load(Yii::$app->request->post())) 
            {
                $model->kayittarihi=date('Y-m-d');
                $model->bakimtarihi=imdat::tomysqldate($model->bakimtarihi);

                if (UploadedFile::getInstance($model,'file')) {
                            $model->file =UploadedFile::getInstance($model,'file'); 
                            $ext = $model->file->extension;
                            $model->sozlesme = Yii::$app->security->generateRandomString().".{$ext}";
                            $path = Yii::getAlias('@env_dosya') ."bgys/".md5("bakim")."/".$model->sozlesme;
                            $model->file->saveAs($path);
                    }
                    $model->file=null;
                    
                $model->bakimformlari = UploadedFile::getInstances($model, 'bakimlar');

                if ($model->bakimformlari) {
                    $images = [];
                    $i=0;
                    foreach ($model->bakimformlari as $file) {

                            $ext = $file->extension;
                            
                        $img_name = Yii::$app->getSecurity()->generateRandomString().".{$ext}";    

                        $path = Yii::getAlias('@env_dosya') ."bgys/".md5("bakim")."/".$img_name;    
                        $file->saveAs($path);   
                        
                        $eskiformlar[] = $img_name;
                        //array_push($eskiformlar,$img_name);
                        //$images[$i]= $img_name;
                        //$i++;
                    }

                    $images=json_encode($eskiformlar);
                    $model->bakimformlari = $images;
                }else{
                    $model->bakimformlari = json_encode($eskiformlar);
                } 
                    $model->bakimlar=null;


                    if ($model->validate()) {     
                        //var_dump($model->errors);exit;           
                            
                        if ($model->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }else{
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }                 
            


            

            return $this->render('update', [
                'model' => $model,
            ]);
        }

    /**
     * Deletes an existing Bgyscihazbakim model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        //echo "<pre>";var_dump($this->findModel($id)->belge);exit;
        $yol2=Yii::$app->basePath .'/web/uploads/bgys/'.md5("bakim")."/". $this->findModel($id)->sozlesme;
        //var_dump($yol);exit;
        try {
            //echo "<pre>";var_dump($this->findModel($id));exit;
            if ($this->findModel($id)->sozlesme and file_exists($yol2)) {
                //echo 1;exit;
                unlink($yol2);
            } 
            if ($this->findModel($id)->bakimformlari ) {
                $formlar=$this->findModel($id)->bakimformlari;
                $formlar=json_decode($formlar);
                foreach ($formlar as $key => $value) {
                    $yol=Yii::$app->basePath .'/web/uploads/bgys/'.md5("bakim")."/". $value;
                    if (file_exists($yol)) {
                        unlink($yol);        
                     } 
                }
                //echo 1;exit;
                
            }
            //echo 2;exit;
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

    /**
     * Finds the Bgyscihazbakim model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgyscihazbakim the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgyscihazbakim::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
