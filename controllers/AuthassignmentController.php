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
    private const BGYS_ROLE_DESCRIPTIONS = [
        'BGYS_Super_Admin' => 'BGYS tüm yetkilere sahip',
        'BGYS_Ekip_Lideri' => 'BGYS ekip lideri',
        'BGYS_Ekip_Uyesi' => 'BGYS ekip üyesi',
        'BGYS_Yonetim_Temsilcisi' => 'BGYS yönetim temsilcisi',
    ];

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
        $searchModel = new AuthassignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($item_name, $user_id)
    {
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('view', [
            'model' => $this->findModel($item_name, $user_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Authassignment();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $this->syncAsbunetAssignment($model->item_name, $model->user_id);
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'yetki atama','atama:'.$model->user_id."<=".$model->item_name );
                    $transaction->commit();
                    //return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
                    return $this->redirect(['index']);
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Rol ataması yapılırken hata oluştu.');
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($item_name, $user_id)
    {
        $model = $this->findModel($item_name, $user_id);
        $oldItemName = $model->item_name;
        $oldUserId = $model->user_id;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $this->removeAsbunetAssignment($oldItemName, $oldUserId);
                    $this->syncAsbunetAssignment($model->item_name, $model->user_id);
                    bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'yetki guncelleme','atama:'.$model->user_id."<=".$model->item_name );
                    $transaction->commit();
                   // return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
                    return $this->redirect(['index']);
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Rol ataması güncellenirken hata oluştu.');
            }
        }

        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('update', [
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
                    $this->removeAsbunetAssignment($item_name, $user_id);
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

    private function syncAsbunetAssignment($itemName, $userId)
    {
        if (!$this->isBgysRole($itemName)) {
            return;
        }

        $asbunetUserId = $this->resolveAsbunetUserId($userId);
        if ($asbunetUserId === null) {
            return;
        }

        $this->ensureAsbunetRole($itemName);

        $exists = (new \yii\db\Query())
            ->from('auth_assignment')
            ->where([
                'item_name' => $itemName,
                'user_id' => (string)$asbunetUserId,
            ])
            ->one(Yii::$app->dbasbunet);

        if ($exists !== false && $exists !== null) {
            return;
        }

        Yii::$app->dbasbunet->createCommand()->insert('auth_assignment', [
            'item_name' => $itemName,
            'user_id' => (string)$asbunetUserId,
            'created_at' => time(),
        ])->execute();
    }

    private function removeAsbunetAssignment($itemName, $userId)
    {
        if (!$this->isBgysRole($itemName)) {
            return;
        }

        $asbunetUserId = $this->resolveAsbunetUserId($userId);
        if ($asbunetUserId === null) {
            return;
        }

        Yii::$app->dbasbunet->createCommand()->delete('auth_assignment', [
            'item_name' => $itemName,
            'user_id' => (string)$asbunetUserId,
        ])->execute();
    }

    private function ensureAsbunetRole($itemName)
    {
        $exists = (new \yii\db\Query())
            ->from('auth_item')
            ->where(['name' => $itemName])
            ->one(Yii::$app->dbasbunet);

        if ($exists !== false && $exists !== null) {
            return;
        }

        Yii::$app->dbasbunet->createCommand()->insert('auth_item', [
            'name' => $itemName,
            'type' => 1,
            'description' => self::BGYS_ROLE_DESCRIPTIONS[$itemName] ?? $itemName,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();
    }

    private function resolveAsbunetUserId($bgysUserId)
    {
        $username = (new \yii\db\Query())
            ->from('user')
            ->select('username')
            ->where(['id' => (string)$bgysUserId])
            ->scalar(Yii::$app->db);

        if (!$username) {
            return null;
        }

        $asbunetUserId = (new \yii\db\Query())
            ->from('user')
            ->select('id')
            ->where(['username' => $username])
            ->scalar(Yii::$app->dbasbunet);

        return $asbunetUserId === false ? null : $asbunetUserId;
    }

    private function isBgysRole($itemName)
    {
        return array_key_exists((string)$itemName, self::BGYS_ROLE_DESCRIPTIONS);
    }

    protected function findModel($item_name, $user_id)
    {
        if (($model = Authassignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
