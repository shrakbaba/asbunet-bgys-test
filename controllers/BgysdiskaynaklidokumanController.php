<?php

namespace app\controllers;

use Yii;
use app\models\Bgysdiskaynaklidokuman;
use app\models\BgysdiskaynaklidokumanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BgysdiskaynaklidokumanController implements the CRUD actions for Bgysdiskaynaklidokuman model.
 */
class BgysdiskaynaklidokumanController extends Controller
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
                'rules' => [                    
                    [
                        'allow' => true,
                        'actions' => ['index','view','create','update'],
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
        $searchModel = new BgysdiskaynaklidokumanSearch();
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
        $model = new Bgysdiskaynaklidokuman();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model=$this->findModel($id);

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                //bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'firma degerlendirme silme','firma degerlendirme:'.$this->findModel($id)->firmaid);
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
        if (($model = Bgysdiskaynaklidokuman::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
