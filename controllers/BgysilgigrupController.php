<?php

namespace app\controllers;

use Yii;
use app\models\Bgysilgigrup;
use app\models\BgysilgigrupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * BgysilgigrupController implements the CRUD actions for Bgysilgigrup model.
 */
class BgysilgigrupController extends Controller
{
    public function behaviors()
    {
        return [
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

    /**
     * Lists all Bgysilgigrup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgysilgigrupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgysilgigrup model.
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
     * Creates a new Bgysilgigrup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bgysilgigrup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bgysilgigrup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
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

    /**
     * Finds the Bgysilgigrup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgysilgigrup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgysilgigrup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
