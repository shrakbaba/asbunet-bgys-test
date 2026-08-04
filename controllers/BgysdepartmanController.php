<?php

namespace app\controllers;

use Yii;
use app\models\Bgysdepartman;
use app\models\BgysdepartmanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\helpers\bgys;
/**
 * BgysdepartmanController implements the CRUD actions for Bgysdepartman model.
 */
class BgysdepartmanController extends Controller
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
                        'roles' => ['BGYS_Ekip_Lideri'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Ekip_Lideri'],
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

    /**
     * Lists all Bgysdepartman models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgysdepartmanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgysdepartman model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bgysdepartman model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bgysdepartman();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'departman tanimlama','departman:'.$model->departman);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bgysdepartman model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'departman guncelleme','departman:'.$model->departman);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bgysdepartman model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
         $model=$this->findModel($id);
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'lokasyon silme','lokasyon:'.$this->findModel($id)->lokasyon);
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

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bgysdepartman model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgysdepartman the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgysdepartman::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
