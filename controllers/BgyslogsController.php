<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Bgyslogs;
use app\models\BgyslogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\bgys;

/**
 * BgyslogsController implements the CRUD actions for Bgyslogs model.
 */
class BgyslogsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','login','contact','view'],
                        'roles' => ['BGYS_Ekip_Lideri'],
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
     * Lists all Bgyslogs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgyslogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgyslogs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Bgyslogs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgyslogs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgyslogs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
