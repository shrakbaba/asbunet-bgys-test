<?php

namespace app\controllers;

use Yii;
use app\models\Bgysdiftalep;
use app\models\Bgysdiftakip;
use app\models\BgysdiftalepSearch;
use app\models\Bgysrisk;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\imdat;
use yii\filters\AccessControl;
use yii\helpers\bgys;
use app\models\Userbilgi;

/**
 * BgysdiftalepController implements the CRUD actions for Bgysdiftalep model.
 */
class BgysdiftalepController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','create','update','delete','difformuac','difform','difac','difformuacoto','deneme'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['diftakiponay','diftakiponayiptal'],
                        'roles' => ['BGYS_Yonetim_Temsilcisi'],
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
        $searchModel = new BgysdiftalepSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysdiftalep();

        $difno=intval(Bgysdiftalep::find()->max('dif_no'))+1;
        $model->dif_no=$difno;
        $model->risk_iliskisi=null;

        if ($model->load(Yii::$app->request->post())) {

            $riskiliskisi=Yii::$app->request->post()['Bgysdiftalep']['risk_iliskisi'];
            if ($riskiliskisi) {
                $model->risk_iliskisi=json_encode($riskiliskisi);                
            }
            $model->planlanan_tarih=imdat::tomysqldate($model->planlanan_tarih);
            $model->olusturan_kisi=Yii::$app->user->id;
            if ($model->save()) {
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'diftalep tanimlama','dif talep:'.$model->dif_konusu);
               return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error','Hata oluştu.');
                return $this->redirect(['index']);
            }
        }
        return $this->renderAjax('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ( !imdat::difformonaydurumu($model->id) ) {

            $model->planlanan_tarih = date("d/m/Y", strtotime($model->planlanan_tarih));

            if ($model->load(Yii::$app->request->post()) ) { 
                $riskiliskisi=Yii::$app->request->post()['Bgysdiftalep']['risk_iliskisi'];
                if ($riskiliskisi) {
                    $model->risk_iliskisi=json_encode($riskiliskisi);                
                }

                $model->planlanan_tarih=imdat::tomysqldate($model->planlanan_tarih);
                $model->olusturan_kisi=Yii::$app->user->id;
                if ($model->save()) {
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'diftalep guncelleme','dif talep:'.$model->dif_konusu);
                   return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error','Hata oluştu.');
                    return $this->redirect(['index']);
                }
            }
            $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$render('update', [
                'model' => $model,
            ]);
        }else{
            Yii::$app->session->setFlash('error','Onaylanmış Dif Formu Mevcut.');
            return $this->redirect(['index']);
        }
    }

    public function actionDifac($id)
    {
        //echo "<pre>";echo date("Y-m-d H:i:s");;exit;
        $riskid=$id;
        $risk=Bgysrisk::findOne($id);
        if ($risk === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $riskadi=$risk->risk;

        $mevcutDif = Bgysdiftalep::find()
            ->where(['regexp', 'risk_iliskisi', '(^|[^0-9])' . intval($riskid) . '([^0-9]|$)'])
            ->orWhere(['dif_konusu' => $riskadi . " adlı risk için açılan DİF kaydı."])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($mevcutDif !== null) {
            if (imdat::difform($mevcutDif->id)) {
                if (Yii::$app->request->isAjax) {
                    return $this->actionDifform($mevcutDif->id, imdat::difformonaydurumu($mevcutDif->id) ? 1 : 0);
                }

                return $this->redirect([
                    'difform',
                    'i' => $mevcutDif->id,
                    'a' => imdat::difformonaydurumu($mevcutDif->id) ? 1 : 0,
                ]);
            }

            if (Yii::$app->request->isAjax) {
                return $this->actionDifformuac($mevcutDif->id);
            }

            return $this->redirect(['difformuac', 'i' => $mevcutDif->id]);
        }

        $diftalep = new Bgysdiftalep();
        $diftalep->dif_no=strval(intval(Bgysdiftalep::find()->max('dif_no'))+1);
        $diftalep->olusturan_kisi=Yii::$app->user->id;
        $diftalep->dif_konusu=$riskadi." adlı risk için açılan DİF kaydı.";
        $diftalep->risk_iliskisi=json_encode([intval($riskid)]);
        $kisi=Yii::$app->user->identity->attributes ;

        if (Yii::$app->params['giristipi']==1) {
            $diftalep->talep_eden=@Userbilgi::findOne(['kisi_id'=>$kisi['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$kisi['id']])->soyad.' / '.$kisi['username'];
        }else{
            $diftalep->talep_eden=$kisi['ad'].' '.$kisi['soyad'].' / '.$kisi['username'];
        } 
        
        //var_dump($diftalep->talep_eden);exit;
        //$model->talep_eden=$kisi['ad']." ".$kisi['soyad'];

        // $model->talep_eden=@Userbilgi::findOne(['kisi_id'=>$kisi['id']])->ad.' '.@Userbilgi::findOne(['kisi_id'=>$kisi['id']])->soyad.
        $diftalep->durum=0;
        $diftalep->talep_tarihi=date("Y-m-d H:i:s");
        $diftalep->planlanan_tarih=date("Y-m-d H:i:s");

        $model = new Bgysdiftakip();
        $model->kokneden=$risk->yuksek_riskin_sebebi;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $diftalep->dif_no=strval(intval(Bgysdiftalep::find()->max('dif_no'))+1);
                if (!$diftalep->save()) {
                    throw new \Exception('DİF talep kaydı oluşturulamadı.');
                }

                $model->diftalep_id=$diftalep->id;
                if ($model->tamamlanmatarihi) {
                    $model->tamamlanmatarihi=imdat::tomysqldate($model->tamamlanmatarihi);
                }
                $model->dif_sorumlusu=Yii::$app->user->id;

                if (!$model->save()) {
                    throw new \Exception('DİF takip kaydı oluşturulamadı.');
                }

                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'diftalep otomatik','dif talep:'.$diftalep->dif_konusu);
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif takip otomatik','dif talep:'.$model->kokneden);
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error','Hata oluştu.');
            }
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$render('diftakip', ['model' => $model,'diftalep' => $diftalep]);
    }

    public function actionDeneme($a,$b,$c=0)
    {
        echo $a."<br>".$b."<br>".$c."<br>";
    }

     public function actionDifformuacoto($id, $rsk)
    {
        $model = new Bgysdiftakip();
        $diftalep=Bgysdiftalep::findOne($id);

        $risk=Bgysrisk::findOne($rsk);
        $model->kokneden=$risk->yuksek_riskin_sebebi;

        if ($model->load(Yii::$app->request->post())) {
            
            $model->diftalep_id=$id;
           // var_dump($model->tamamlanmatarihi);exit;
            if ($model->tamamlanmatarihi) {
                $model->tamamlanmatarihi=imdat::tomysqldate($model->tamamlanmatarihi);
            }
            $model->dif_sorumlusu=Yii::$app->user->id;
            
            //$model->validate(); 
            //echo "<pre>";var_dump($model->errors);exit;

            if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif takip otomatik','dif talep:'.$model->kokneden);
               return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error','Hata oluştu.');
                return $this->redirect(['index']);
            }   
        }
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$render('diftakip', ['model' => $model,'diftalep' => $diftalep]);
    }

    public function actionDifformuac($i)
    {
       // echo "geldin";exit;
        $model = new Bgysdiftakip();
        $diftalep=Bgysdiftalep::findOne($i);

        if ($model->load(Yii::$app->request->post())) {
            
            $model->diftalep_id=$i;
            
            if ($model->tamamlanmatarihi) {
                $model->tamamlanmatarihi=imdat::tomysqldate($model->tamamlanmatarihi);
            }
            $model->dif_sorumlusu=Yii::$app->user->id;
            
            //$model->validate(); 
            //echo "<pre>";var_dump($model->errors);exit;

            if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif takip','dif talep:'.$model->kokneden);
               return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error','Hata oluştu.');
                return $this->redirect(['index']);
            }   
        }
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$render('diftakip', ['model' => $model,'diftalep' => $diftalep]);
    }

    public function actionDifform($i,$a)
    {
        $model = Bgysdiftakip::findOne(['diftalep_id'=>$i]);
        
        $talepid=$model->diftalep_id;
        $diftalep=Bgysdiftalep::findOne($talepid);
        
        if ($a) {
        	if ($model->tamamlanmatarihi) {
            	$model->tamamlanmatarihi = imdat::mysqltowebdate(date('Y-m-d',strtotime($model->tamamlanmatarihi)));
            }

            //$model->tamamlanmatarihi = imdat::mysqltowebdate(date('Y-m-d',strtotime($model->tamamlanmatarihi)));
            $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$render('onaylidiftakip', ['model' => $model,'diftalep' => $diftalep]);
        }else{
           // $model->tamamlanmatarihi = imdat::mysqltowebdate(date('Y-m-d',strtotime($model->tamamlanmatarihi)));

            //echo "<pre>";var_dump($model->tamamlanmatarihi);exit;
            if ($model->tamamlanmatarihi) {
            	$model->tamamlanmatarihi = imdat::mysqltowebdate(date('Y-m-d',strtotime($model->tamamlanmatarihi)));
            }


            if ($model->load(Yii::$app->request->post())) {

            
                $model->diftalep_id=$i;
           // echo "<pre>";var_dump($model->tamamlanmatarihi);exit;
            if ($model->tamamlanmatarihi) {
            	$model->tamamlanmatarihi=imdat::tomysqldate($model->tamamlanmatarihi);
            }

               // $model->tamamlanmatarihi=imdat::tomysqldate($model->tamamlanmatarihi);
                $model->dif_sorumlusu=Yii::$app->user->id;

                if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif formu tanimlama','dif talep:'.$model->kokneden);
                   return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error','Hata oluştu.');
                    return $this->redirect(['index']);
                }   
            }
            $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
            return $this->$render('diftakip', ['model' => $model,'diftalep' => $diftalep]);
        }
    }

     public function actionDiftakiponay($i)
    {
        $model = Bgysdiftakip::findOne(['id'=>$i]);
        $model->onay=1;

        if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif takip onay','dif takip:'.$i);
            Yii::$app->session->setFlash('success','Dif Onaylandı.') ;
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error','Hata oluştu.') ;
                return $this->redirect(['index']);
        }

    }
     public function actionDiftakiponayiptal($i)
    {
        $model = Bgysdiftakip::findOne(['id'=>$i]);
        $model->onay=0;

        if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif takip onay iptal','dif takip:'.$i);
            Yii::$app->session->setFlash('info','Dif Onayı Kaldırıldı.') ;
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error','Hata oluştu.') ;
                return $this->redirect(['index']);
        }

    }

    public function actionDelete($id)
    {
            $model=$this->findModel($id);
            if ( !imdat::difformonaydurumu($model->id)) {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'dif talep silme','dif takip:'.$this->findModel($id)->dif_no);
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
            
            }else{
                Yii::$app->session->setFlash('error','Onaylanmış Dif Formu Mevcut.');
                return $this->redirect(['index']);
            }
    }

    /**
     * Finds the Bgysdiftalep model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgysdiftalep the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgysdiftalep::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
