<?php

namespace app\controllers;

use Yii;
use app\models\Bgysvarlikenvanteri;
use app\models\BgysvarlikenvanteriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\imdat;
use yii\filters\AccessControl;

use app\models\Bgysolasilik;
use app\models\Bgyskategori;
use app\models\Bgyseylemmatrisi;
use app\models\Bgyssiddettablosu;
use app\models\Bgysbilgisinifi;
use yii\helpers\bgys;

/**
 * BgysvarlikenvanteriController implements the CRUD actions for Bgysvarlikenvanteri model.
 */
class BgysvarlikenvanteriController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','create','update','delete','bilgiler'],
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
        $searchModel = new BgysvarlikenvanteriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBilgiler()
    {
        $bgysolasilik = Bgysolasilik::find()->all();
        $bgyskategori = Bgyskategori::find()->all();
        $bgyseylemmatrisi = Bgyseylemmatrisi::find()->all();
        $bgyssiddettablosu = Bgyssiddettablosu::find()->all();
        $bgysbilgisinifi = Bgysbilgisinifi::find()->all();

        return $this->render('bilgiler', [
            'bgysolasilik' => $bgysolasilik,
            'bgyskategori' => $bgyskategori,
            'bgyseylemmatrisi' => $bgyseylemmatrisi,
            'bgyssiddettablosu' => $bgyssiddettablosu,
            'bgysbilgisinifi' => $bgysbilgisinifi,
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
        $model = new Bgysvarlikenvanteri();

        if ($model->load(Yii::$app->request->post())) {
            $model->varlik_degeri=round(($model->gizlilik+$model->erisilebilirlik+$model->butunluk)/3);
           
            if ($model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'varlik tanımlandı','varlik:'.$model->varlik_adi );
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                Yii::$app->session->setFlash('error','Kayıt sırasında hata oluştu.');                
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

        if ($model->load(Yii::$app->request->post())) {
            $model->varlik_degeri=round(($model->gizlilik+$model->erisilebilirlik+$model->butunluk)/3);
           
            if ($model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'varlik guncellendi','varlik:'.$model->varlik_adi );
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                Yii::$app->session->setFlash('error','Kayıt sırasında hata oluştu.');                
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
            $model=$this->findModel($id);
            //if ( !imdat::difformonaydurumu($model->id)) {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'varlik silindi','varlik:'.$this->findModel($id)->varlik_adi );
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
            
            /*}else{
                Yii::$app->session->setFlash('error','Onaylanmış Dif Formu Mevcut.');
                return $this->redirect(['index']);
            }*/
    }

    protected function findModel($id)
    {
        if (($model = Bgysvarlikenvanteri::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
