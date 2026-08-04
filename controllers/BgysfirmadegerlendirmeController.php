<?php

namespace app\controllers;

use Yii;
use app\models\Bgysfirmadegerlendirme;
use app\models\BgysfirmadegerlendirmeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * FirmadegerlendirmeController implements the CRUD actions for Firmadegerlendirme model.
 */
class BgysfirmadegerlendirmeController extends Controller
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
                        'actions' => ['index','view'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['onay','onaykaldir'],
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
        $searchModel = new BgysfirmadegerlendirmeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOnay($i)
    {
        if (Yii::$app->user->can('BGYS_Yonetim_Temsilcisi')) {
          
            $model =Bgysfirmadegerlendirme::findOne(['id' => $i]);
            if ($model) {
                $model->onay=1;

                $model->save();
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme onay','firma degerlendirme:'.$model->firmaid);
            }else{
                Yii::$app->session->setFlash('warning','Kayıt bulunamadı.');
                return $this->redirect('index');
            }
            Yii::$app->session->setFlash('success','Değerlendirme Onaylandı.');
            return $this->redirect('index');
        }else{
            Yii::$app->session->setFlash('error','Yetkisiz işlem.');
            return $this->redirect(['index']); 

        }
    }
     public function actionOnaykaldir($i)
    {
        if (Yii::$app->user->can('BGYS_Yonetim_Temsilcisi')) {
          
            $model =Bgysfirmadegerlendirme::findOne(['id' => $i]);
            if ($model) {
                $model->onay=0;
                $model->save();
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme onay kaldir','firma degerlendirme:'.$model->firmaid);
            }else{
                Yii::$app->session->setFlash('warning','Kayıt bulunamadı.');
                return $this->redirect('index');
            }
            Yii::$app->session->setFlash('success','Değerlendirme Onayı kaldırıldı.');
            return $this->redirect('index');
        }else{
            Yii::$app->session->setFlash('error','Yetkisiz işlem.');
            return $this->redirect(['index']); 

        }
    }

    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysfirmadegerlendirme();

        if ($model->load(Yii::$app->request->post())) {
            $model->degerlendiren=Yii::$app->user->identity->id; 
            $model->kriter1=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter1']);
            $model->kriter2=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter2']);
            $model->kriter3=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter3']);
            $model->kriter4=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter4']);
            $model->kriter5=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter5']);
            $model->kriter6=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter6']);
            $model->kriter7=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter7']);
            $model->kriter8=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter8']);
            $model->kriter9=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter9']);
            $model->kriter10=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter10']);
            $model->onay=0;
            $model->degerlendirmetarihi= date("Y-m-d H:i:s");
            //var_dump($model->degerlendirmetarihi);exit;

            /*$model->toplam=ceil((($model->kriter1+$model->kriter2+$model->kriter3+$model->kriter4+$model->kriter5+$model->kriter6+$model->kriter7+$model->kriter8+$model->kriter9+$model->kriter10+$model->kriter11+$model->kriter12)/6)*10);*/

            $model->toplam=($model->kriter1+$model->kriter2+$model->kriter3+$model->kriter4+$model->kriter5+$model->kriter6+$model->kriter7+$model->kriter8+$model->kriter9+$model->kriter10);

            /*$varmi = Bgysfirmadegerlendirme::find()
                ->where(['firmaid' => $model->firmaid, 'degerlendiren'=>$model->degerlendiren])
                ->one();
            if ($varmi==null) { */
                if ($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme tanimlama','firma degerlendirme:'.$model->firmaid);
                    return $this->redirect(['index']);
                }
            //}else
            //    Yii::$app->session->setFlash('error','Bu firma için kayıt girilmiş.');
        }
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->onay) {
       
            $model->scenario = 'update';

            if ($model->load(Yii::$app->request->post()) ){
                $model->kriter1=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter1']);
                $model->kriter2=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter2']);
                $model->kriter3=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter3']);
                $model->kriter4=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter4']);
                $model->kriter5=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter5']);
                $model->kriter6=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter6']);
                $model->kriter7=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter7']);
                $model->kriter8=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter8']);
                $model->kriter9=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter9']);
                $model->kriter10=intval(Yii::$app->request->post()['Bgysfirmadegerlendirme']['kriter10']);
               // $model->toplam=ceil((($model->kriter1+$model->kriter2+$model->kriter3+$model->kriter4+$model->kriter5+$model->kriter6+$model->kriter7+$model->kriter8+$model->kriter9+$model->kriter10)/6)*10);
                $model->toplam=ceil((($model->kriter1+$model->kriter2+$model->kriter3+$model->kriter4+$model->kriter5+$model->kriter6+$model->kriter7+$model->kriter8+$model->kriter9+$model->kriter10)));

               // $varmi = Firmadegerlendirme::find()
                //    ->where(['firmaid' => $model->firmaid, 'degerlendiren'=>$model->degerlendiren])
                //    ->one();
                //if ($varmi==null) {
                    if ($model->save()) {
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme guncelleme','firma degerlendirme:'.$model->firmaid);
                        return $this->redirect(['index']);
                    }
                //}else
                //    Yii::$app->session->setFlash('error','Bu firma için kayıt girilmiş.');
                
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }else{
            Yii::$app->session->setFlash('error','Onaylanmış Değerlendirme.');
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if (!$model->onay) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme silme','firma degerlendirme:'.$this->findModel($id)->firmaid);
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
            Yii::$app->session->setFlash('error','Onaylanmış Değerlendirme.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Firmadegerlendirme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Firmadegerlendirme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgysfirmadegerlendirme::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
