<?php

namespace app\controllers;

use Yii;
use app\components\RecordAccess;
use app\models\Bgysolaykayit;
use app\models\Bgysolaykayitbelge;
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
use yii\helpers\FileHelper;


class BgysolaykayitController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'pdfsil' => ['POST'],
                    'belgesil' => ['POST'],
                    'belgeguncelle' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','belgegoster','pdfgoster'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete','pdfsil','belgesil','belgeguncelle'],
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
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysolaykayit();
        $maillistesi = bgys::mailGrubu('olayKaydi');
        if ($model->load(Yii::$app->request->post()) ) {

            $model->olaytarihi=imdat::tomysqldate($model->olaytarihi);
            $model->mudahaletarihi=imdat::tomysqldate($model->mudahaletarihi);        
            $model->userid=Yii::$app->user->identity->id;

            $model->file = UploadedFile::getInstances($model,'file');
            if ($model->save() and (count($maillistesi) ? bgys::olaykayitbildirim($maillistesi,$model->konu,$model->sonuc) : true) ) {
                $this->olayBelgeleriniKaydet($model, Yii::$app->request->post('replace_belge_id'), Yii::$app->request->post('replace_legacy'));
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay tanımlandı','olay:'.$model->konu );
                Yii::$app->session->setFlash('success','Olay kaydı oluşturuldu. Olay No: '.$model->id);
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
            return $this->redirect(['index']);



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
        RecordAccess::assertCanManage($model, ['userid'], 'bgys_olay_kayit');
        $maillistesi = bgys::mailGrubu('olayKaydi');

        $model->olaytarihi=$this->mysqlTarihiWebTarihineCevir($model->olaytarihi);
        $model->mudahaletarihi=$this->mysqlTarihiWebTarihineCevir($model->mudahaletarihi); 

        if ($model->load(Yii::$app->request->post())) {

            $model->olaytarihi=imdat::tomysqldate($model->olaytarihi);
            $model->mudahaletarihi=imdat::tomysqldate($model->mudahaletarihi);

            
            $model->file = UploadedFile::getInstances($model,'file');
            if ($model->save() and (count($maillistesi) ? bgys::olaykayitbildirim($maillistesi,$model->konu,$model->sonuc) : true) ) {
                $this->olayBelgeleriniKaydet($model, Yii::$app->request->post('replace_belge_id'), Yii::$app->request->post('replace_legacy'));
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay guncellendi','olay:'.$model->konu );
                Yii::$app->session->setFlash('success','Olay kaydı güncellendi.');
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
            return $this->redirect(['index']);

            /*if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }*/
        }

        return Yii::$app->request->isAjax
            ? $this->renderAjax('update', [
                'model' => $model,
            ])
            : $this->render('update', [
                'model' => $model,
            ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        RecordAccess::assertCanManage($model, ['userid'], 'bgys_olay_kayit');

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
        $olayId = $i;
        if ($i) {
            RecordAccess::assertCanManage($this->findModel($i), ['userid'], 'bgys_olay_kayit_belge');
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
                    $path = Yii::$app->basePath .'/web/uploads/bgys/'.md5("olay")."/". $belge;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay belgesi silindi','olay:'.$i);
                    Yii::$app->session->setFlash('success','Belge Silindi.');
                }else
                {
                    Yii::$app->session->setFlash('error','Hata');
                }
            }else{
                Yii::$app->session->setFlash('error','Belge bulunamadı.');
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'reloadUrl' => \yii\helpers\Url::to(['update', 'id' => $olayId])];
            }
            return $this->redirect('update?id='.$i);
        }else{
            Yii::$app->session->setFlash('error','Belge bulunamadı.');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'message' => 'Belge bulunamadı.'];
            }
            return $this->redirect("index");
        }
         
    }

    public function actionPdfgoster($id)
    {
        $model = $this->findModel($id);
        if (!$model->belge) {
            throw new NotFoundHttpException('Belge bulunamadı.');
        }

        $path = $this->olayBelgeYolu($model->belge);
        if (!file_exists($path)) {
            throw new NotFoundHttpException('Belge dosyası bulunamadı.');
        }

        return Yii::$app->response->sendFile($path, $model->belge, [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionBelgegoster($id)
    {
        $belge = Bgysolaykayitbelge::findOne($id);
        if ($belge === null || $belge->olay === null) {
            throw new NotFoundHttpException('Belge bulunamadı.');
        }

        $path = $this->olayBelgeYolu($belge->dosya);
        if (!file_exists($path)) {
            throw new NotFoundHttpException('Belge dosyası bulunamadı.');
        }

        return Yii::$app->response->sendFile($path, $belge->orijinal_ad, [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionBelgesil($id)
    {
        $belge = Bgysolaykayitbelge::findOne($id);
        if ($belge === null) {
            Yii::$app->session->setFlash('error','Belge bulunamadı.');
            return $this->redirect(['index']);
        }

        RecordAccess::assertCanManage($belge->olay, ['userid'], 'bgys_olay_kayit_belge');
        $olayId = $belge->olay_id;
        $path = $this->olayBelgeYolu($belge->dosya);
        if (file_exists($path)) {
            unlink($path);
        }
        $belge->delete();
        bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay belgesi silindi','olay:'.$olayId);
        Yii::$app->session->setFlash('success','Belge silindi.');

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true, 'reloadUrl' => \yii\helpers\Url::to(['update', 'id' => $olayId])];
        }

        return $this->redirect(['update', 'id' => $olayId]);
    }

    public function actionBelgeguncelle()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName('replace_file');
        $belgeId = Yii::$app->request->post('belge_id');
        $legacyId = Yii::$app->request->post('legacy_id');

        if ($file === null) {
            return ['success' => false, 'message' => 'PDF dosyası seçilmedi.'];
        }

        if (strtolower($file->extension) !== 'pdf' || $file->size > 1024 * 1024) {
            return ['success' => false, 'message' => 'Sadece 1 MB değerinden küçük PDF dosyası yüklenebilir.'];
        }

        if (FileHelper::getMimeType($file->tempName) !== 'application/pdf') {
            return ['success' => false, 'message' => 'Yüklenen dosya geçerli bir PDF dosyası değil.'];
        }

        $klasor = Yii::getAlias('@env_dosya') ."bgys/".md5("olay")."/";
        FileHelper::createDirectory($klasor);
        $dosya = Yii::$app->security->generateRandomString().'.'.$file->extension;

        if ($belgeId) {
            $belge = Bgysolaykayitbelge::findOne($belgeId);
            if ($belge === null) {
                return ['success' => false, 'message' => 'Belge bulunamadı.'];
            }

            RecordAccess::assertCanManage($belge->olay, ['userid'], 'bgys_olay_kayit_belge');
            $olayId = $belge->olay_id;
            $eskiYol = $this->olayBelgeYolu($belge->dosya);
            if ($file->saveAs($klasor.$dosya)) {
                if (file_exists($eskiYol)) {
                    unlink($eskiYol);
                }
                $belge->dosya = $dosya;
                $belge->orijinal_ad = $file->name;
                $belge->created_at = date('Y-m-d H:i:s');
                $belge->created_by = Yii::$app->user->identity->id;
                $belge->save(false);
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay belgesi güncellendi','olay:'.$olayId);
                return ['success' => true, 'reloadUrl' => \yii\helpers\Url::to(['update', 'id' => $olayId])];
            }
        }

        if ($legacyId) {
            $model = $this->findModel($legacyId);
            RecordAccess::assertCanManage($model, ['userid'], 'bgys_olay_kayit_belge');
            $eskiYol = $this->olayBelgeYolu($model->belge);
            if ($file->saveAs($klasor.$dosya)) {
                if ($model->belge && file_exists($eskiYol)) {
                    unlink($eskiYol);
                }
                $model->belge = $dosya;
                $model->save(false, ['belge']);
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'olay belgesi güncellendi','olay:'.$model->id);
                return ['success' => true, 'reloadUrl' => \yii\helpers\Url::to(['update', 'id' => $model->id])];
            }
        }

        return ['success' => false, 'message' => 'Belge güncellenemedi.'];
    }

    protected function findModel($id)
    {
        if (($model = Bgysolaykayit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function olayBelgeleriniKaydet(Bgysolaykayit $model, $replaceBelgeId = null, $replaceLegacy = null)
    {
        if (empty($model->file)) {
            return;
        }

        $klasor = Yii::getAlias('@env_dosya') ."bgys/".md5("olay")."/";
        FileHelper::createDirectory($klasor);

        if ($replaceLegacy && $model->belge) {
            $eskiYol = $this->olayBelgeYolu($model->belge);
            if (file_exists($eskiYol)) {
                unlink($eskiYol);
            }
            $model->belge = null;
            $model->save(false, ['belge']);
        }

        $replaceBelge = null;
        if ($replaceBelgeId) {
            $replaceBelge = Bgysolaykayitbelge::findOne(['id' => $replaceBelgeId, 'olay_id' => $model->id]);
        }

        foreach ($model->file as $file) {
            $dosya = Yii::$app->security->generateRandomString().'.'.$file->extension;
            if ($file->saveAs($klasor.$dosya)) {
                $belge = $replaceBelge ?: new Bgysolaykayitbelge();
                if ($replaceBelge) {
                    $eskiYol = $this->olayBelgeYolu($replaceBelge->dosya);
                    if (file_exists($eskiYol)) {
                        unlink($eskiYol);
                    }
                }
                $belge->olay_id = $model->id;
                $belge->dosya = $dosya;
                $belge->orijinal_ad = $file->name;
                $belge->created_at = date('Y-m-d H:i:s');
                $belge->created_by = Yii::$app->user->identity->id;
                $belge->save(false);
                $replaceBelge = null;
            }
        }
    }

    private function olayBelgeYolu($dosya)
    {
        return Yii::getAlias('@env_dosya') ."bgys/".md5("olay")."/".$dosya;
    }

    private function mysqlTarihiWebTarihineCevir($date)
    {
        if (!$date || $date === '0000-00-00') {
            return null;
        }

        $tarih = explode('-', substr($date, 0, 10));
        if (count($tarih) !== 3) {
            return null;
        }

        return $tarih[2]."/".$tarih[1]."/".$tarih[0];
    }
}
