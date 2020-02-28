<?php

namespace app\controllers;

use Yii;
use app\models\Bgyskritiksurecler;
use app\models\BgyskritiksureclerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * BgyskritiksureclerController implements the CRUD actions for Bgyskritiksurecler model.
 */
class BgyskritiksureclerController extends Controller
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
     * Lists all Bgyskritiksurecler models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgyskritiksureclerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgyskritiksurecler model.
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
     * Creates a new Bgyskritiksurecler model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bgyskritiksurecler();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bgyskritiksurecler model.
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
     * Finds the Bgyskritiksurecler model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgyskritiksurecler the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgyskritiksurecler::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
