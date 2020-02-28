<?php

namespace app\controllers;

use Yii;
use app\models\Userbilgi;
use app\models\UserbilgiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\bgys;
use yii\helpers\imdat;
use yii\filters\AccessControl;

/**
 * UserbilgiController implements the CRUD actions for Userbilgi model.
 */
class UserbilgiController extends Controller
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
                        'actions' => ['create','index','view','delete'],
                        'roles' => ['BGYS_Yonetim_Temsilcisi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
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
     * Lists all Userbilgi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserbilgiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userbilgi model.
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

    public function actionUpdate($id)
    {
        //$model = $this->findone(['id' => $id]);
        //$user=Yii::$app->user->identity->id;
        /*var_dump($user);*/
        //var_dump($id);exit;

        if ( is_numeric(($id)) and @imdat::userbilgibenimmi($id) and $id==Yii::$app->user->identity->id) {
        
          $model = @Userbilgi::find()->where(['kisi_id' => $id])->one();

          if ($model->load(Yii::$app->request->post())) {            
              if ($model->save()) {  
                  return $this->redirect(['view', 'id' => $model->id]);
              }
          }
          return $this->render('update', [
              'model' => $model,
          ]);

        }else{

            Yii::$app->session->setFlash('error','Yetkisiz işlem.');
            return $this->render('/site/index');
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Userbilgi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
