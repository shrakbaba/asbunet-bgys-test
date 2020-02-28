<?php

namespace app\controllers;

use Yii;
use app\models\Bgysrisk;
use app\models\BgysriskSearch;
use app\models\Bgysriskkabul;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\BgysriskkabulSearch;
use yii\helpers\bgys;
use app\models\Bgysdiftalep;

/**
 * BgysriskController implements the CRUD actions for Bgysrisk model.
 */
class BgysriskController extends Controller
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
                        'actions' => ['create','update','delete','riskkabuller'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['riskkabul','riskkabuldelete'],
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
    public $iliskiler ;
    public function actionIndex()
    {
        $searchModel = new BgysriskSearch();
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
        $model = new Bgysrisk();

        if ($model->load(Yii::$app->request->post())) {
            $model->riskdegeri_onceki=max($model->gizlilik_onceki,$model->butunluk_onceki,$model->erisilebilirlik_onceki)*$model->olasilik_onceki*$model->varlik0->varlik_degeri;
             $model->riskdegeri_sonraki=null;
             $model->ozetdurum=3;
            if ($model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'risk tanımlandı','risk:'.$model->risk );
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->riskdegeri_onceki=max($model->gizlilik_onceki,$model->butunluk_onceki,$model->erisilebilirlik_onceki)*$model->olasilik_onceki*$model->varlik0->varlik_degeri;
            /*if ($model->gizlilik_sonraki==null ) {    
                $model->gizlilik_sonraki=intval($model->gizlilik_onceki);
            }
            if ($model->butunluk_sonraki==null ) {    
                $model->butunluk_sonraki=intval($model->butunluk_onceki);
            }
            if ($model->erisilebilirlik_sonraki==null ) {    
                $model->erisilebilirlik_sonraki=intval($model->erisilebilirlik_onceki);
            }*/
           /* var_dump($model->gizlilik_sonraki);echo "<br>";
            var_dump($model->butunluk_sonraki);echo "<br>";
            var_dump($model->erisilebilirlik_sonraki);echo "<br>";
            var_dump($model->olasilik_sonraki);echo "<br>";
            var_dump($model->varlik0->varlik_degeri);echo "<br>";exit;*/

            if ($model->gizlilik_sonraki!=null or $model->butunluk_sonraki!=null or $model->erisilebilirlik_sonraki!=null or $model->olasilik_sonraki!=null )
            {
                if ($model->gizlilik_sonraki==null ) {    
                    $model->gizlilik_sonraki=($model->gizlilik_onceki);
                }
                if ($model->butunluk_sonraki==null ) {    
                    $model->butunluk_sonraki=($model->butunluk_onceki);
                }
                if ($model->erisilebilirlik_sonraki==null ) {    
                    $model->erisilebilirlik_sonraki=($model->erisilebilirlik_onceki);
                }
                if ($model->olasilik_sonraki==null ) {    
                    $model->olasilik_sonraki=($model->olasilik_sonraki);
                }

                $model->riskdegeri_sonraki=max(intval($model->gizlilik_sonraki),intval($model->butunluk_sonraki),intval($model->erisilebilirlik_sonraki))*intval($model->olasilik_sonraki)*$model->varlik0->varlik_degeri;
            }
                   
            
            if ($model->riskdegeri_sonraki==0 ) {
                $model->riskdegeri_sonraki=null;
                $model->ozetdurum=3;
            }elseif($model->riskdegeri_sonraki<$model->riskdegeri_onceki){
                $model->ozetdurum=1;
            }elseif($model->riskdegeri_sonraki>$model->riskdegeri_onceki){
                $model->ozetdurum=2;
            }else{                
                $model->ozetdurum=3;
            }

            if ($model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'risk guncellendi','risk:'.$model->risk );
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
     
   /* public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    public function actionDelete($id)
    {
        $model=$this->findModel($id);
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'risk silindi','risk:'.$this->findModel($id)->risk );
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

    public function actionRiskkabul($id)
    {
        $model = new Bgysriskkabul();

        if ($model->load(Yii::$app->request->post())) {
             $model->riskid=$id;
             $model->kabuleden=Yii::$app->user->id;
            if ($model->save()) {

                $model2 = Bgysrisk::findOne(intval($id));
                $model2->updateAttributes(['ozetdurum' => 4]);

                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'risk kabul','risk:'.$model->riskid );
                return $this->redirect(['index']);
            }
        }
        return $this->render('riskkabul', [
            'model' => $model,
        ]);

    }

    public function actionRiskkabuller()
    {
        $searchModel = new BgysriskkabulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('riskkabuller', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRiskkabuldelete($id)
    {
        
            $model=Bgysriskkabul::findOne($id);
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {

                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'risk kabul','risk:'.$model->riskid );
                $model->delete();

                $model2 = Bgysrisk::findOne(intval($id));
                $model2->updateAttributes(['ozetdurum' => 3]);

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
        if (($model = Bgysrisk::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
