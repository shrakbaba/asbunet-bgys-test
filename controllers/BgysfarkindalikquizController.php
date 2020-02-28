<?php

namespace app\controllers;

use Yii;
use app\models\Bgysfarkindalikquiz;
use app\models\BgysfarkindalikquizSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\helpers\bgys;
/**
 * BgysfarkindalikquizController implements the CRUD actions for Bgysfarkindalikquiz model.
 */
class BgysfarkindalikquizController extends Controller
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
                        'actions' => ['index','view','delete'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','egitim'],
                        'roles' => [],
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
        $searchModel = new BgysfarkindalikquizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionEgitim()
    {
        $ip=Yii::$app->request->userIP;
        $ipkayitlimi=Bgysfarkindalikquiz::findOne(['ip'=>$ip]);
        if ($ipkayitlimi) {        
            if(count($ipkayitlimi)){
                $a=1;
            }else{
                $a=0;
            }
        }else{
                $a=0;
            }
        return $this->render('egitim',['a'=>$a]);
        
    }

    public function actionView($id)
    {
        //var_dump((new Bgysfarkindalikquiz())->dogrular);exit;
        return $this->render('view', [
            'model' => $this->findModel($id),
            //'dogrular' => $a,
        ]);
    }

    public function actionCreate()
    {
        $model = new Bgysfarkindalikquiz();

        if ($model->load(Yii::$app->request->post()) ){

            $sended=Yii::$app->request->post()['Bgysfarkindalikquiz'];
            $model->ip=Yii::$app->request->userIP;

            $cevaplar=[
                'soru1'=>$sended['soru1'],
                'soru2'=>$sended['soru2'],
                'soru3'=>$sended['soru3'],
                'soru4'=>$sended['soru4'],
                'soru5'=>$sended['soru5'],
                'soru6'=>$sended['soru6'],
                'soru7'=>$sended['soru7'],
                'soru8'=>$sended['soru8'],
                'soru9'=>$sended['soru9'],
                'soru10'=>$sended['soru10'],
                'soru11'=>$sended['soru11'],

            ];
            $model->cevaplar=json_encode($cevaplar);

        $d=0;
        foreach ($cevaplar as $key => $value) {
            if ($value!=null) {
               //var_dump($model->dogrular[$key]);echo "==>";var_dump(intval($value));echo "<br>";
                if ($model->dogrular[$key]==intval($value)) {
                    $d++; //doğru cevap sayısı
                }
            }
        }

        $model->puan=strval(round(((100/count($model->dogrular))*$d),2));
        //var_dump($d);exit;
        $ip=Yii::$app->request->userIP;
        $ipkayitlimi=Bgysfarkindalikquiz::findOne(['ip'=>$ip]);
        if(count($ipkayitlimi)){ //ip den başvuru olmuş
            $a=1;
        }else{
            $a=0;
        }
            if ($a==0) {    
            //$model->validate();
            //var_dump($model->errors);exit;        
                if($model->save()) {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'farkindalik quiz tanimlama','farkindalik:'.$model->cevaplayan);
                    Yii::$app->session->setFlash('success','Sonuçlarınız başarıyla alındı. Teşekkürler.');
                    return $this->redirect(['egitim']);
                }else{                
                    Yii::$app->session->setFlash('error','Kayıt sırasında hata oluştu.');
                    return $this->redirect(['egitim']);
                }
            }else{
                Yii::$app->session->setFlash('error','Bu ip adresinden kayıt yapılmış.');
                    return $this->redirect(['egitim']);
            }
        } 

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

   /* public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    */
     public function actionDelete($id)
    {
            $model=$this->findModel($id);
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,Yii::$app->user->identity->id,'farkindalik quiz silme','farkindalik:'.$this->findModel($id)->cevaplayan);
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
        if (($model = Bgysfarkindalikquiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
