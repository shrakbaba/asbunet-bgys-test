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
                        'actions' => ['create','delete'],
                        'roles' => ['BGYS_Super_Admin'],
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
        if (!Yii::$app->user->isGuest) {
            Userbilgi::adBilgileriniSenkronla(Yii::$app->user->identity);
        }

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
        $this->senkronla($id);
        $model = $this->findModel($id);
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('view', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $superAdmin = Yii::$app->user->can('BGYS_Super_Admin');
        $model = null;

        if (is_numeric($id) && !Yii::$app->user->isGuest && (int)$id === (int)Yii::$app->user->identity->id) {
            $model = Userbilgi::find()->where(['kisi_id' => (int)$id])->one();
        }
        if ($model === null) {
            $model = $this->findModelByIdOrKisiId($id);
        }

        if ($model === null && is_numeric($id) && (int)$id === (int)Yii::$app->user->identity->id) {
            Userbilgi::adBilgileriniSenkronla(Yii::$app->user->identity);
            $model = Userbilgi::find()->where(['kisi_id' => (int)$id])->one();
        }

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!$superAdmin && (int)$model->kisi_id !== (int)Yii::$app->user->identity->id) {
            Yii::$app->session->setFlash('error','Yetkisiz işlem.');
            return $this->render('/site/index');
        }

        $this->senkronla($model->id);
        $model = $this->findModel($model->id);

        if ($model->load(Yii::$app->request->post())) {
            $mevcutModel = $this->findModel($model->id);
            $model->kisi_id = $mevcutModel->kisi_id;
            $model->ad = $mevcutModel->ad;
            $model->soyad = $mevcutModel->soyad;
            $model->email = $mevcutModel->email;

            if ($model->save()) {
                Yii::$app->session->setFlash('success','Bilgiler güncellendi.');
                return Yii::$app->request->isAjax
                    ? '<script>window.location.reload();</script>'
                    : ($superAdmin ? $this->redirect(['index']) : $this->redirect(['/site/index']));
            }
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        return $this->$render('update', [
            'model' => $model,
        ]);
    }

    private function senkronla($id)
    {
        $model = $this->findModelByIdOrKisiId($id);
        if ($model === null) {
            return;
        }

        if (!Yii::$app->user->isGuest && (int)$model->kisi_id === (int)Yii::$app->user->identity->id) {
            Userbilgi::adBilgileriniSenkronla(Yii::$app->user->identity);
            return;
        }

        try {
            $identityClass = \Edvlerblog\Adldap2\model\UserDbLdap::className();
            $identity = $identityClass::findOne($model->kisi_id);
            if ($identity !== null) {
                Userbilgi::adBilgileriniSenkronla($identity);
            }
        } catch (\Throwable $e) {
            Yii::warning('Kullanıcı bilgisi görüntüleme öncesi senkronlanamadı: ' . $e->getMessage(), 'security');
        }
    }

    private function findModelByIdOrKisiId($id)
    {
        $model = Userbilgi::findOne($id);
        if ($model !== null) {
            return $model;
        }

        return Userbilgi::find()->where(['kisi_id' => $id])->one();
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
