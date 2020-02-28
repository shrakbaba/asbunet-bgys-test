<?php

namespace app\controllers;

use Yii;
use app\models\Bgysyedeklemelistesi;
use app\models\BgysyedeklemelistesiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * BgysyedeklemelistesiController implements the CRUD actions for Bgysyedeklemelistesi model.
 */
class BgysyedeklemelistesiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','login','view','create','update','delete'],
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
        $searchModel = new BgysyedeklemelistesiSearch();
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
        $model = new Bgysyedeklemelistesi();
        if ($model->load(Yii::$app->request->post())){            
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
        if (($model = Bgysyedeklemelistesi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
