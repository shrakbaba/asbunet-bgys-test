<?php

namespace app\controllers;

use Yii;
use app\models\Authitemchild;
use app\models\AuthitemchildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * AuthitemchildController implements the CRUD actions for Authitemchild model.
 */
class AuthitemchildController extends Controller
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
                        'roles' => ['BGYS_Super_Admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Super_Admin'],
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
        $searchModel = new AuthitemchildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($parent, $child)
    {
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('view', [
            'model' => $this->findModel($parent, $child),
        ]);
    }

    public function actionCreate()
    {
        $model = new Authitemchild();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'parent atama','atama:'.$model->parent."=>".$model->child );
            return Yii::$app->request->isAjax
                ? '<script>window.location.reload();</script>'
                : $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($parent, $child)
    {
        $model = $this->findModel($parent, $child);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'parent guncelleme','atama:'.$model->parent."=>".$model->child );
            return Yii::$app->request->isAjax
                ? '<script>window.location.reload();</script>'
                : $this->redirect(['index']);
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($parent, $child)
    {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'parent silme','atama:'.$parent."=>".$child );
                    $this->findModel($parent, $child)->delete();
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

    protected function findModel($parent, $child)
    {
        if (($model = Authitemchild::findOne(['parent' => $parent, 'child' => $child])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
