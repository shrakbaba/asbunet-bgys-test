<?php

namespace app\controllers;

use Yii;
use app\models\Envmarka;
use app\models\EnvmarkaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * EnvmarkaController implements the CRUD actions for Envmarka model.
 */
class EnvmarkaController extends Controller
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
        $searchModel = new EnvmarkaSearch();
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
        $model = new Envmarka();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->syncDeviceTypes($model);
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'marka tanımlandı','marka:'.$model->marka );
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->syncDeviceTypes($model);
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'marka guncellendi','marka:'.$model->marka );
           // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
       /* $this->findModel($id)->delete();

        return $this->redirect(['index']);*/
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'marka silindi','marka:'.$this->findModel($id)->marka );
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
        if (($model = Envmarka::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function syncDeviceTypes(Envmarka $model)
    {
        $usedTypeIds = (new \yii\db\Query())->select('cihaz_turu_id')->distinct()->from('env_cihaz_liste')
            ->where(['marka_id' => $model->id])->column();
        $model->cihaz_turu_ids = array_unique(array_merge((array)$model->cihaz_turu_ids, $usedTypeIds));
        Yii::$app->db->createCommand()->delete('env_cihaz_turu_marka', ['marka_id' => $model->id])->execute();
        $rows = [];
        foreach (array_unique(array_map('intval', (array)$model->cihaz_turu_ids)) as $typeId) {
            if ($typeId > 0) {
                $rows[] = [$typeId, $model->id];
            }
        }
        if ($rows) {
            Yii::$app->db->createCommand()->batchInsert('env_cihaz_turu_marka', ['cihaz_turu_id', 'marka_id'], $rows)->execute();
        }
    }
}
