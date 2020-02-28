<?php

namespace app\controllers;

use Yii;
use app\models\Mailkapat;
use app\models\MailkapatSearch;
use app\models\Authassignment;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\imdat;
use yii\helpers\bgys;
use yii\filters\AccessControl;

/**
 * MailkapatController implements the CRUD actions for Mailkapat model.
 */
class MailkapatController extends Controller
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
        $kapanacaklar=Mailkapat::find(['kapatildi'=>0])->all();  //Kapatılmamış tüm mailler
        if ($kapanacaklar) {
            foreach ($kapanacaklar as $key => $value) {
                $mailhesabi=@$value->mailhesabi;
                $ayrilistarihi=@$value->ayrilistarihi;
                $maillistesi=[];

                $yonetimtemsilcisi=Authassignment::find()->where(['item_name'=>'BGYS_Yonetim_Temsilcisi'])->all();
                if ($yonetimtemsilcisi) {
                    foreach ($yonetimtemsilcisi as $key2 => $value2) {
                        $un = $value2->user->username;
                       // echo "<pre>";var_dump($un);exit;
                        //Get the Ldap object for the user.
                        //$ldapObject holds a class of type Adldap\Models\User from the Adldap project!
                        // Method 1: uses the default provider given in the configuration above (array key defaultProvider)
                        $ldapObject = \Yii::$app->ad->search()->findBy('sAMAccountname', $un);
                        // Method 2: uses the default provider given in the configuration above (array key defaultProvider)
                        //$ldapObject = \Yii::$app->ad->getDefaultProvider()->search()->findBy('sAMAccountname', $un);
                        // Method 3: get the provider by name (here name default is used).
                        //$ldapObject = \Yii::$app->ad->getProvider('default')->search()->findBy('sAMAccountname', $un);

                        //$givenName = $ldapObject['givenname'][0];
                        //$surname = $ldapObject['sn'][0];
                        //$displayname = $ldapObject['displayname'][0];
                        $mail = $ldapObject['mail'][0];

                        //Print all possible attributes
                        //echo '<pre>' . print_r($ldapObject,true) . '</pre>';
                        //echo "<pre>";var_dump($mail);exit;
                        array_push($maillistesi,$mail);
                    }
                        array_push($maillistesi,'ali.eren@asbu.edu.tr');
                }
                $a=5;  //5. 10. 15. günlerde mail at
                for ($i=0; $i <3 ; $i++) {  
                    $y=($i+1)*$a;
                    $uyaritarihi=date('Y-m-d',strtotime("+$y days", strtotime($ayrilistarihi)));
                    //bgys::bakima1hafta(imdat::mysqltowebdate($uyaritarihi), $marka, $model, $key, $service_tag, $maillistesi, imdat::mysqltowebdate($bakimtarihi));
                           
                    if (date("Y-m-d")==$uyaritarihi) {
                        $a=bgys::mailikapat(imdat::mysqltowebdate($uyaritarihi), $mailhesabi, $y, $maillistesi, imdat::mysqltowebdate($ayrilistarihi));
                    }
                }//echo date("Y-m-d")."<br>";
            }
        }
    }

    /**
     * Lists all Mailkapat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MailkapatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mailkapat model.
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
     * Creates a new Mailkapat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mailkapat();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->ayrilistarihi=imdat::tomysqldate($model->ayrilistarihi);
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mailkapat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->ayrilistarihi=imdat::mysqltowebdate($model->ayrilistarihi);

        if ($model->load(Yii::$app->request->post())){

            $model->ayrilistarihi=imdat::tomysqldate($model->ayrilistarihi);
            if($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mailkapat model.
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
     * Finds the Mailkapat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mailkapat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mailkapat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
