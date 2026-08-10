<?php

namespace app\controllers;

use Yii;
use app\models\Envmodel;
use app\models\EnvmodelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\db\IntegrityException;
use yii\helpers\bgys;

/**
 * EnvmodelController implements the CRUD actions for Envmodel model.
 */
class EnvmodelController extends Controller
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
                        'roles' => ['BGYS_Ekip_Lideri','bilgiislem_admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete'],
                        'roles' => ['BGYS_Ekip_Lideri','bilgiislem_admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['lists'],
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

    public function actionIndex()
    {
        $searchModel = new EnvmodelSearch();
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
        $model = new Envmodel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->syncDeviceTypes($model);

            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'model tanımlandı','model:'.$model->model );
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

       // return $this->render('create', [
       //     'model' => $model,
       // ]);
        
        return $this->renderAjax('create', [
            'model' => $model
        ]);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->syncDeviceTypes($model);

            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'model guncellendi','model:'.$model->model );
            
            return $this->redirect(Yii::$app->request->referrer);
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
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'model silindi','model:'.$this->findModel($id)->model );
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
     * Finds the Envmodel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Envmodel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Envmodel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function syncDeviceTypes(Envmodel $model)
    {
        $usedTypeIds = (new \yii\db\Query())->select('cihaz_turu_id')->distinct()->from('env_cihaz_liste')
            ->where(['model_id' => $model->id])->column();
        $model->cihaz_turu_ids = array_unique(array_merge((array)$model->cihaz_turu_ids, $usedTypeIds));
        Yii::$app->db->createCommand()->delete('env_cihaz_turu_model', ['model_id' => $model->id])->execute();
        $rows = [];
        foreach (array_unique(array_map('intval', (array)$model->cihaz_turu_ids)) as $typeId) {
            if ($typeId > 0) {
                $rows[] = [$typeId, $model->id];
            }
        }
        if ($rows) {
            Yii::$app->db->createCommand()->batchInsert('env_cihaz_turu_model', ['cihaz_turu_id', 'model_id'], $rows)->execute();
        }
    }


    public function actionLists($id)
    {
        
        $modelsayisi = Envmodel::find()
                ->where(['marka_id' => $id])
                ->count();

        $modeller = Envmodel::find()
                ->where(['marka_id' => $id])
                ->orderBy('id DESC')
                ->all();

        if($modelsayisi>0){
            foreach($modeller as $model){
                echo "<option value='".$model->id."'>".$model->model."</option>";
            }
        }
        else{
            echo "<option>-</option>";
        }
        
        //echo "<option>-</option>";

    }
}
