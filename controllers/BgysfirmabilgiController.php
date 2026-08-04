<?php

namespace app\controllers;

use Yii;
use app\models\Bgysfirmabilgi;
use app\models\BgysfirmabilgiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\bgys;
/**
 * FirmabilgiController implements the CRUD actions for Firmabilgi model.
 */
class BgysfirmabilgiController extends Controller
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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','belge'],
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
        $searchModel = new BgysfirmabilgiSearch();
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

    public function actionBelge($id)
    {
        $model = $this->findModel($id);
        if (!$model->belge) {
            throw new NotFoundHttpException('Belge bulunamadı.');
        }

        $path = Yii::$app->basePath . '/web/uploads/bgys/' . md5("firma") . '/' . $model->belge;
        if (!is_file($path)) {
            throw new NotFoundHttpException('Belge dosyası bulunamadı.');
        }

        return Yii::$app->response->sendFile($path, $model->belge, [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysfirmabilgi();
        //echo md5("firma");exit;

        if ($model->load(Yii::$app->request->post())) {
            $model->faaliyet_alani = $this->faaliyetAlaniniTemizle($model->faaliyet_alani);
            if (empty($model->faaliyet_alani)) {
                $model->faaliyet_alani = null;
            }

            if (!$model->validate()) {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }

            if ($model->faaliyet_alani) {
                $model->faaliyet_alani=json_encode($model->faaliyet_alani);
            }

            $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->belge = Yii::$app->security->generateRandomString().".{$ext}";
                    $path = Yii::getAlias('@env_dosya') ."bgys/".md5("firma")."/".$model->belge;

                    if ($model->validate()) {
                        if ($model->save(false)) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma bilgi tanimlama','firma:'.$model->firmaadi);
                            $model->file->saveAs($path);
                            return $this->redirect(['index']);
                        }
                    }else{
                        Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                        return $this->redirect(['index']);
                    }
                }  else{
                    if ($model->save(false)) { 
                        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma bilgi tanimlama','firma:'.$model->firmaadi);

                        Yii::$app->session->setFlash('success','Firma Kaydedildi.');
                        return $this->redirect(['index']);
                    }
                } 
            //return $this->redirect(['view', 'id' => $model->id]);
            //return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) { 
            $model->faaliyet_alani = $this->faaliyetAlaniniTemizle($model->faaliyet_alani);
            if (empty($model->faaliyet_alani)) {
                $model->faaliyet_alani = null;
            }

            if (!$model->validate()) {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }

            if ($model->faaliyet_alani) {
                $model->faaliyet_alani=json_encode($model->faaliyet_alani);
             }

            $model->file =UploadedFile::getInstance($model,'file'); 
            if ($model->file!=null) {  
                    $ext = $model->file->extension;
                    $model->belge = Yii::$app->security->generateRandomString().".{$ext}";
                    $path = Yii::getAlias('@env_dosya') ."/bgys/".md5("firma")."/".$model->belge;

                    if ($model->validate()) {
                        if ($model->save(false)) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma bilgi guncelleme','firma:'.$model->firmaadi);
                            $model->file->saveAs($path);
                        return $this->redirect(['index']);
                        }
                    }else{
                        Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                        return $this->redirect(['index']);
                    }
                }  else{
                    if ($model->save(false)) { 
                        return $this->redirect(['index']);
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
        try {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma bilgi silme','firma:'.$this->findModel($id)->firmaadi);
            $this->findModel($id)->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success','Silme işlemi başarılı.');
            return $this->redirect(['index']);

        } catch (IntegrityException $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error','Silme işleminde hata oluştu.');
            return $this->redirect(['index']);

        }catch (\Exception $e) {

            $transaction->rollBack();
            Yii::$app->session->setFlash('error','Silme işleminde hata oluştu.');
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
                ->update('bgys_firma_bilgi', ['belge'=>null], 'id='.$i)
                ->execute();
                if ($a) {
                    unlink(Yii::$app->basePath .'/web/uploads/bgys/'.md5("firma")."/". $belge);
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
        if (($model = Bgysfirmabilgi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function faaliyetAlaniniTemizle($faaliyetAlanlari)
    {
        $temiz = [];
        foreach ((array)$faaliyetAlanlari as $faaliyetAlani) {
            $faaliyetAlani = trim((string)$faaliyetAlani);
            if ($faaliyetAlani !== '') {
                $temiz[] = $faaliyetAlani;
            }
        }

        return $temiz;
    }
}
