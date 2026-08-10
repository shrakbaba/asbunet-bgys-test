<?php

namespace app\controllers;

use Yii;
use app\components\RecordAccess;
use app\components\SecureFileStorage;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
                        'actions' => ['index','view','download'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
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
               if ($cihaz && $cihaz->zimmet) { //zimmet yapılmışsa
                    $zimmetEmail = bgys::zimmetEmail($cihaz->zimmet);
                    if ($zimmetEmail) {
                        array_push($maillistesi, $zimmetEmail);
                    }
               }

		        $maillistesi = bgys::mailListesiOlustur('cihazBakim', $maillistesi);

               /*$yonetimtemsilcisi=Authassignment::find()->where(['item_name'=>'BGYS_Yonetim_Temsilcisi'])->all();
               //$temsilciemailleri=[];
                if ($yonetimtemsilcisi) {
                    foreach ($yonetimtemsilcisi as $key2 => $value2) {
                        array_push($maillistesi,$value2->user->email);
                    }
                }*/
            
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
                }
            }
        }
    }

    public function actionIndex()
    {
        $searchModel = new BgyscihazbakimSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgyscihazbakim();

        if ($model->load(Yii::$app->request->post())) 
            {
                $model->kayittarihi=Yii::$app->formatter->asDate(time(), 'php:Y-m-d');
                
                $model->bakimtarihi=imdat::tomysqldate($model->bakimtarihi);

                $model->file = UploadedFile::getInstance($model, 'file');
                $model->bakimlar = UploadedFile::getInstances($model, 'bakimlar');

                if ($model->validate()) {
                    $storedFiles = [];
                    try {
                        if ($model->file) {
                            $model->sozlesme = SecureFileStorage::storePdf($model->file, 'maintenance');
                            $storedFiles[] = $model->sozlesme;
                        }
                        $formFiles = [];
                        foreach ($model->bakimlar as $file) {
                            $stored = SecureFileStorage::storePdf($file, 'maintenance');
                            $formFiles[] = $stored;
                            $storedFiles[] = $stored;
                        }
                    } catch (\Throwable $exception) {
                        $this->deleteMaintenanceFiles($storedFiles);
                        throw $exception;
                    }
                    $model->bakimformlari = $formFiles ? json_encode($formFiles) : null;
                    $model->file = null;
                    $model->bakimlar = null;

                    if ($model->save(false)) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->session->setFlash('success','Bakım kaydı oluşturuldu.');
                            return '<script>window.location.reload();</script>';
                        }
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        $this->deleteMaintenanceFiles($storedFiles);
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
        RecordAccess::assertCanManage($model, ['sorumlu'], 'bgys_cihaz_bakim');
        $eskiformlar=json_decode($model->bakimformlari ?: '[]', true);
        if (!is_array($eskiformlar)) {
            $eskiformlar = [];
        }
        $eskiSozlesme = $model->sozlesme;
        //var_dump($eskiformlar);exit;
         $model->bakimtarihi = date("d/m/Y", strtotime($model->bakimtarihi));

        if ($model->load(Yii::$app->request->post())) 
            {
                $model->kayittarihi=Yii::$app->formatter->asDate(time(), 'php:Y-m-d');
                $model->bakimtarihi=imdat::tomysqldate($model->bakimtarihi);

                $model->file = UploadedFile::getInstance($model, 'file');
                $model->bakimlar = UploadedFile::getInstances($model, 'bakimlar');

                if ($model->validate()) {
                    $storedFiles = [];
                    try {
                        if ($model->file) {
                            $model->sozlesme = SecureFileStorage::storePdf($model->file, 'maintenance');
                            $storedFiles[] = $model->sozlesme;
                        } else {
                            $model->sozlesme = $eskiSozlesme;
                        }
                        foreach ($model->bakimlar as $file) {
                            $stored = SecureFileStorage::storePdf($file, 'maintenance');
                            $storedFiles[] = $stored;
                            $eskiformlar[] = $stored;
                        }
                    } catch (\Throwable $exception) {
                        $this->deleteMaintenanceFiles($storedFiles);
                        throw $exception;
                    }
                    $model->bakimformlari = $eskiformlar ? json_encode($eskiformlar) : null;
                    $model->file = null;
                    $model->bakimlar = null;

                    if ($model->save(false)) {
                            if ($eskiSozlesme && $eskiSozlesme !== $model->sozlesme) {
                                SecureFileStorage::delete($eskiSozlesme, 'maintenance', [$this->legacyMaintenanceDirectory()]);
                            }
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->session->setFlash('success','Bakım kaydı güncellendi.');
                                return '<script>window.location.reload();</script>';
                            }
                            return $this->redirect(['view', 'id' => $model->id]);
                        }else{
                            $this->deleteMaintenanceFiles($storedFiles);
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }                 
            


            

            $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$renderMethod('update', [
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
        $model = $this->findModel($id);
        RecordAccess::assertCanManage($model, ['sorumlu'], 'bgys_cihaz_bakim');

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        //echo "<pre>";var_dump($this->findModel($id)->belge);exit;
        try {
            SecureFileStorage::delete($model->sozlesme, 'maintenance', [$this->legacyMaintenanceDirectory()]);
            $formlar = json_decode($model->bakimformlari ?: '[]', true);
            $this->deleteMaintenanceFiles(is_array($formlar) ? $formlar : [], true);
            $model->delete();
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

    public function actionDownload($id, $type, $index = null)
    {
        $model = $this->findModel($id);
        if ($type === 'contract') {
            $fileName = $model->sozlesme;
            $downloadName = 'bakim-sozlesmesi-' . $model->id . '.pdf';
        } elseif ($type === 'form') {
            $forms = json_decode($model->bakimformlari ?: '[]', true);
            $index = filter_var($index, FILTER_VALIDATE_INT);
            if (!is_array($forms) || $index === false || !array_key_exists($index, $forms)) {
                throw new NotFoundHttpException('Bakım formu bulunamadı.');
            }
            $fileName = $forms[$index];
            $downloadName = 'bakim-formu-' . $model->id . '-' . ($index + 1) . '.pdf';
        } else {
            throw new NotFoundHttpException('Belge türü bulunamadı.');
        }

        if (!$fileName) {
            throw new NotFoundHttpException('Belge bulunamadı.');
        }
        $path = SecureFileStorage::find($fileName, 'maintenance', [$this->legacyMaintenanceDirectory()]);
        bgys::logtut($this->id, $this->action->id, Yii::$app->user->id, 'bakım belgesi indirildi', 'bakım:' . $model->id . ';tür:' . $type);
        return Yii::$app->response->sendFile($path, $downloadName, [
            'mimeType' => 'application/pdf',
            'inline' => false,
        ]);
    }

    private function deleteMaintenanceFiles(array $fileNames, $includeLegacy = false)
    {
        $legacy = $includeLegacy ? [$this->legacyMaintenanceDirectory()] : [];
        foreach ($fileNames as $fileName) {
            SecureFileStorage::delete($fileName, 'maintenance', $legacy);
        }
    }

    private function legacyMaintenanceDirectory()
    {
        return Yii::$app->basePath . '/web/uploads/bgys/' . md5('bakim');
    }
}
