<?php

namespace app\controllers;

use Yii;
use app\components\RecordAccess;
use app\components\SecureFileStorage;
use app\models\Envcihazliste;
use app\models\EnvcihazlisteSearch;
use app\models\Envcihazzimmet;
use app\models\Authassignment;
use app\models\Envcihazturu;
use app\models\Envmarka;
use app\models\Envmodel;
use app\models\Bgysvarlikenvanteri;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use yii\helpers\imdat;
use yii\web\UploadedFile;
use yii\helpers\bgys;
use yii\data\ActiveDataProvider;
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
                        'actions' => ['index','view','dashboard','download','catalog-options','data-quality'],
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
                $model->created_by = Yii::$app->user->id;
                $model->alim_tarihi=imdat::tomysqldate($model->alim_tarihi);
                $model->garanti_bitis=imdat::tomysqldate($model->garanti_bitis);
                //echo $model->alim_tarihi;echo "<br>";echo $model->garanti_bitis;exit;

                $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    if ($model->validate()) {
                        $model->dosya = SecureFileStorage::storePdf($model->file, 'devices');
                        if ($model->save(false)) {
                            $this->syncAssignmentHistory($model, null);
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz tanımlandı','cihaz:'.$model->id );
                            //return $this->redirect(['view', 'id' => $model->id]);                            
                            //return $this->redirect(['index']);
                            return $this->redirect(Yii::$app->request->referrer);
                        }else{
                            SecureFileStorage::delete($model->dosya, 'devices');
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save()) { 
                        $this->syncAssignmentHistory($model, null);
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
        RecordAccess::assertCanManage($model, ['created_by'], 'env_cihaz_liste');
        $oldZimmet = $model->zimmet;
        $oldFileName = $model->dosya;
        
        $model->alim_tarihi=imdat::mysqltowebdate($model->alim_tarihi);
        $model->garanti_bitis=imdat::mysqltowebdate($model->garanti_bitis); 

        if ($model->load(Yii::$app->request->post()) )
            {
                $model->alim_tarihi=imdat::tomysqldate($model->alim_tarihi);
                $model->garanti_bitis=imdat::tomysqldate($model->garanti_bitis);

                $model->file =UploadedFile::getInstance($model,'file');  
                if ($model->file!=null) {  
                    if ($model->validate()) {
                        $model->dosya = SecureFileStorage::storePdf($model->file, 'devices');
                        if ($model->save(false)) {
                            $this->syncAssignmentHistory($model, $oldZimmet);
                            SecureFileStorage::delete($oldFileName, 'devices', [$this->legacyDeviceDirectory()]);
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz guncellendi','cihaz:'.$model->id );
                            //return $this->redirect(['view', 'id' => $model->id]);
                            return $this->redirect(Yii::$app->request->referrer);
                        }else{
                            SecureFileStorage::delete($model->dosya, 'devices');
                            Yii::$app->session->setFlash('error','Kaydedilemedi. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                    }else{
                            Yii::$app->session->setFlash('error','Hata oluştu. Tekrar deneyiniz.');
                            return $this->redirect(['index']);
                        }
                }  else{
                    if ($model->save()) { 
                            $this->syncAssignmentHistory($model, $oldZimmet);
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
        $model = $this->findModel($id);
        RecordAccess::assertCanManage($model, ['created_by'], 'env_cihaz_liste');
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        //echo "<pre>";var_dump($this->findModel($id)->belge);exit;
        //var_dump($yol);exit;
        try {
            //echo "<pre>";var_dump($this->findModel($id));exit;
            SecureFileStorage::delete($model->dosya, 'devices', [$this->legacyDeviceDirectory()]);
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
            RecordAccess::assertCanManage($this->findModel($i), ['created_by'], 'env_cihaz_liste');
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
                    SecureFileStorage::delete($dosya, 'devices', [$this->legacyDeviceDirectory()]);

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

    public function actionDataQuality($issue = null)
    {
        $query = Envcihazliste::find()->with(['cihazTuru', 'marka', 'model', 'bgysAsset']);
        switch ($issue) {
            case 'bgys':
                $query->andWhere(['bgys_asset_id' => null]);
                break;
            case 'amount':
                $query->andWhere(['or', ['adet' => null], ['<=', 'adet', 0]]);
                break;
            case 'location':
                $query->andWhere(['or', ['konum' => null], ['konum' => '']]);
                break;
            case 'document':
                $query->andWhere(['or', ['dosya' => null], ['dosya' => '']]);
                break;
            case 'expired':
                $query->andWhere(['<', 'garanti_bitis', date('Y-m-d')]);
                break;
            case 'legacy':
                $query->andWhere(['is_legacy' => 1]);
                break;
        }

        $summaryQuery = Envcihazliste::find();
        $summary = [
            'total' => (int)(clone $summaryQuery)->count(),
            'bgys' => (int)(clone $summaryQuery)->where(['bgys_asset_id' => null])->count(),
            'amount' => (int)(clone $summaryQuery)->where(['or', ['adet' => null], ['<=', 'adet', 0]])->count(),
            'location' => (int)(clone $summaryQuery)->where(['or', ['konum' => null], ['konum' => '']])->count(),
            'document' => (int)(clone $summaryQuery)->where(['or', ['dosya' => null], ['dosya' => '']])->count(),
            'expired' => (int)(clone $summaryQuery)->where(['<', 'garanti_bitis', date('Y-m-d')])->count(),
            'legacy' => (int)(clone $summaryQuery)->where(['is_legacy' => 1])->count(),
        ];

        return $this->render('data-quality', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
                'pagination' => ['pageSize' => 50],
            ]),
            'summary' => $summary,
            'issue' => $issue,
        ]);
    }

    public function actionCatalogOptions($typeId, $brandId = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $type = Envcihazturu::findOne((int)$typeId);
        if ($type === null) {
            return ['brands' => [], 'models' => [], 'assets' => [], 'assetType' => null];
        }

        $brands = Envmarka::find()->alias('b')
            ->innerJoin('env_cihaz_turu_marka tm', 'tm.marka_id = b.id')
            ->where(['tm.cihaz_turu_id' => $type->id])->orderBy('b.marka')->select(['id' => 'b.id', 'text' => 'b.marka'])->asArray()->all();
        $models = [];
        if ($brandId) {
            $models = Envmodel::find()->alias('m')
                ->innerJoin('env_cihaz_turu_model tt', 'tt.model_id = m.id')
                ->where(['tt.cihaz_turu_id' => $type->id, 'm.marka_id' => (int)$brandId])
                ->orderBy('m.model')->select(['id' => 'm.id', 'text' => 'm.model'])->asArray()->all();
        }
        $assets = $type->asset_type ? array_map(function ($asset) {
            return ['id' => $asset->id, 'text' => $asset->varlik_adi . ($asset->varlik_sahibi ? ' / ' . $asset->varlik_sahibi : '')];
        }, Bgysvarlikenvanteri::find()->where(['asset_type' => $type->asset_type])->orderBy('varlik_adi')->all()) : [];

        return ['brands' => $brands, 'models' => $models, 'assets' => $assets, 'assetType' => $type->asset_type];
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if (!$model->dosya) {
            throw new NotFoundHttpException('Dosya bulunamadı.');
        }

        $path = SecureFileStorage::find($model->dosya, 'devices', [$this->legacyDeviceDirectory()]);
        return Yii::$app->response->sendFile($path, 'cihaz-belgesi-' . $model->id . '.pdf', [
            'inline' => false,
            'mimeType' => 'application/pdf',
        ]);
    }

    private function legacyDeviceDirectory()
    {
        return Yii::$app->basePath . '/web/uploads/bgys/' . md5('cihaz');
    }

    private function syncAssignmentHistory(Envcihazliste $device, $oldUserId)
    {
        $newUserId = $device->zimmet ? (int)$device->zimmet : null;
        $oldUserId = $oldUserId ? (int)$oldUserId : null;
        $activeAssignment = Envcihazzimmet::find()
            ->where(['cihaz_id' => $device->id, 'iade_tarihi' => null])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($newUserId === $oldUserId && ($newUserId === null || $activeAssignment !== null)) {
            return;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($activeAssignment !== null) {
                $activeAssignment->iade_tarihi = date('Y-m-d H:i:s');
                $activeAssignment->iade_alan_id = Yii::$app->user->id;
                if (!$activeAssignment->save()) {
                    throw new \RuntimeException('Zimmet iade geçmişi kaydedilemedi.');
                }
            }

            if ($newUserId !== null) {
                $assignment = new Envcihazzimmet();
                $assignment->cihaz_id = $device->id;
                $assignment->user_id = $newUserId;
                $assignment->teslim_tarihi = date('Y-m-d H:i:s');
                $assignment->teslim_eden_id = Yii::$app->user->id;
                if (!$assignment->save()) {
                    throw new \RuntimeException('Yeni zimmet geçmişi kaydedilemedi.');
                }
            }

            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::error($exception, 'device-assignment');
            throw $exception;
        }
    }
}
