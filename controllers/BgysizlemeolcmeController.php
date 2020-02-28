<?php

namespace app\controllers;

use Yii;
use app\models\Bgysizlemeolcme;
use app\models\BgysizlemeolcmeSearch;
use app\models\Bgysizlemesonucu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * BgysizlemeolcmeController implements the CRUD actions for Bgysizlemeolcme model.
 */
class BgysizlemeolcmeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','kayitgir','kayitsil','login','view','create','update','delete'],
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

    public function actionIndex()
    {
        $searchModel = new BgysizlemeolcmeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query->where('yil = 2019');
        return $this->render('indextab', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKayitgir($id)
    {
        if (bgys::olcmesorumlumu($id) or Yii::$app->user->can('bilgiislem_admin')) {      
            $sonuclar=Bgysizlemesonucu::find()->where(['izlemeid'=>$id])->all();
            $olcum_sikligi=$this->findModel($id)->olcum_sikligi;        
            //1 =>"Yılda 1", 2 =>"6 Ayda bir", 3 =>"3 Ayda 1", 4 =>"Ayda 1"
            $a=false; 
            if ($olcum_sikligi==1 and count($sonuclar)==0) {
                $a=true;        
            }elseif ($olcum_sikligi==2 and count($sonuclar)<2 ) {
                $a=true;      
            }elseif ($olcum_sikligi==3 and count($sonuclar)<4 ) {
                $a=true;      
            }elseif ($olcum_sikligi==4 and count($sonuclar)<12 ) {
                $a=true;      
            }

            if ($a) {
                $model = new Bgysizlemesonucu();
                if ($model->load(Yii::$app->request->post())){
                    $model->izlemeid = $id;

                    if ( $model->save()) {
                        return $this->redirect(['index']); 
                    }
                }
                return $this->renderAjax('kayitgir', [
                    'model' => $model,
                ]);
            }else{
                Yii::$app->session->setFlash('error','Yeterli sayıda kayıt girilmiş.');
                return $this->redirect(['index']);
            }
        }else{
                Yii::$app->session->setFlash('error','Yetkisiz erişim');
                return $this->redirect(['index']);
        }
    }


    public function actionKayitsil($id)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            //bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'marka silindi','marka:'.$this->findModel($id)->marka );
            Bgysizlemesonucu::findOne($id)->delete();
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


    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'sonuclar'=> @Bgysizlemesonucu::find()->where(['izlemeid'=>$id])->all()
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysizlemeolcme();

        if ($model->load(Yii::$app->request->post())){
            
             $model->planlanan_tarihi= $model->planlanan_tarihi."-01";
            if ( $model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){            
             $model->planlanan_tarihi= $model->planlanan_tarihi."-01";
            if ( $model->save()) {
                return $this->redirect(['index']);
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
            //bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'marka silindi','marka:'.$this->findModel($id)->marka );
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

    protected function findModel($id)
    {
        if (($model = Bgysizlemeolcme::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
