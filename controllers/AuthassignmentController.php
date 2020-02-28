<?php

namespace app\controllers;

use Yii;
use app\models\Authassignment;
use app\models\AuthassignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * AuthassignmentController implements the CRUD actions for Authassignment model.
 */
class AuthassignmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['BGYS_Yonetim_Temsilcisi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
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
        $searchModel = new AuthassignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($item_name, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($item_name, $user_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Authassignment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'yetki atama','atama:'.$model->user_id."<=".$model->item_name );
            //return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($item_name, $user_id)
    {
        $model = $this->findModel($item_name, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'yetki guncelleme','atama:'.$model->user_id."<=".$model->item_name );
           // return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($item_name, $user_id)
    {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'yetki silme','atama:'.$user_id."<=".$item_name );
                    $this->findModel($item_name, $user_id)->delete();
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

    protected function findModel($item_name, $user_id)
    {
        if (($model = Authassignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
