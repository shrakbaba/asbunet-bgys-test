<?php

namespace app\controllers;

use Yii;
use app\models\Yenihostbildir;
use app\models\YenihostbildirSearch;
use app\models\Authassignment;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\imdat;
use yii\helpers\bgys;
use yii\filters\AccessControl;

/**
 * YenihostbildirController implements the CRUD actions for Yenihostbildir model.
 */
class YenihostbildirController extends Controller
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
                        'actions' => ['mailat'],
                        'roles' => [],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
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

    public function actionMailat()  //mail kapatma hatırlatması
    {
        bgys::cronErisiminiDogrula();

        $bildirilecekler=Yenihostbildir::find()
            ->andWhere(['or',
                ['zabbix'=>0],
                ['kaspersky'=>0],
                ['ipmanage'=>0],
                ['paloalto'=>0]]
            )
            ->all();  //Kapatılmamış tüm mailler
        if ($bildirilecekler) {

            foreach ($bildirilecekler as $key => $value) {
                $vm_name=@$value->vm_name;
                $tarihi=@$value->tarihi;

                $maillistesi=bgys::mailGrubu('yeniVm');

                $a=1;  //5. 10. 15. günlerde mail at
                for ($i=0; $i <10 ; $i++) {  
                    $y=($i)*$a;
                    $uyaritarihi=date('Y-m-d',strtotime("+$y days", strtotime($tarihi)));

                    //echo "<pre>";var_dump($uyaritarihi);exit;
                           
                    if (date("Y-m-d")==$uyaritarihi) {
                        bgys::yenivm($maillistesi, $vm_name);
                    }
                }//echo date("Y-m-d")."<br>";
            }
        }
    }

    /**
     * Lists all Yenihostbildir models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new YenihostbildirSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Yenihostbildir model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Yenihostbildir model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Yenihostbildir();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->session->setFlash('success','Sunucu hatırlatma kaydı oluşturuldu.');
                    return '<script>window.location.reload();</script>';
                }
                return $this->redirect(['index']);
        }

        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Yenihostbildir model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->session->setFlash('success','Sunucu hatırlatma kaydı güncellendi.');
                    return '<script>window.location.reload();</script>';
                }
                return $this->redirect(['index']);
        }

        $renderMethod = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Yenihostbildir model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
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
     * Finds the Yenihostbildir model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Yenihostbildir the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Yenihostbildir::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
