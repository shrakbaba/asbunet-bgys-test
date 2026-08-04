<?php

namespace app\controllers;

use Yii;
use app\models\Envcihazturu;
use app\models\EnvcihazturuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;
use yii\db\IntegrityException;

/**
 * EnvcihazturuController implements the CRUD actions for Envcihazturu model.
 */
class EnvcihazturuController extends Controller
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
                        'roles' => ['BGYS_Ekip_Lideri','bilgiislem_admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Ekip_Lideri','bilgiislem_admin'],
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
        $searchModel = new EnvcihazturuSearch();
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
        $model = new Envcihazturu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz turu tanımlandı','tur:'.$model->cihaz_turu );
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz turu guncellendi','tur:'.$model->cihaz_turu );
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
       /* $this->findModel($id)->delete();

        return $this->redirect(['index']);*/
        $model = $this->findModel($id);
        $kullanimSayisi = $model->getEnvCihazListes()->count();
        if ($kullanimSayisi > 0) {
            Yii::$app->session->setFlash('error', 'Bu cihaz türü ' . $kullanimSayisi . ' cihazda kullanıldığı için silinemez.');
            return $this->redirect(['index']);
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'cihaz turu silindi','tur:'.$model->cihaz_turu );
            $model->delete();
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
        if (($model = Envcihazturu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
