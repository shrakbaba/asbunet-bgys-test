<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\bgys;

/**
 * AuthitemController implements the CRUD actions for Authitem model.
 */
class NessusController extends Controller
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
                        'actions' => ['sessionac','klasorac','scanekle','uuidal','folders','listscan','scandevamet'],
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

    private function nessusUrl($path)
    {
        if (empty(Yii::$app->params['nessusBaseUrl'])) {
            throw new BadRequestHttpException('Nessus bağlantı bilgisi tanımlı değil.');
        }
        return rtrim(Yii::$app->params['nessusBaseUrl'] ?? '', '/') . $path;
    }

    private function nessusApiHeader()
    {
        if (empty(Yii::$app->params['nessusAccessKey']) || empty(Yii::$app->params['nessusSecretKey'])) {
            throw new BadRequestHttpException('Nessus API anahtarı tanımlı değil.');
        }
        return 'x-apikeys:accessKey=' . (Yii::$app->params['nessusAccessKey'] ?? '') . ';secretKey=' . (Yii::$app->params['nessusSecretKey'] ?? '');
    }

    private function nessusSslVerifyHost()
    {
        return !empty(Yii::$app->params['nessusVerifySsl']) ? 2 : 0;
    }

    private function nessusSslVerifyPeer()
    {
        return !empty(Yii::$app->params['nessusVerifySsl']);
    }

    public function actionSessionac()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
		  //CURLOPT_URL => "https://www.tenable.com/downloads/api/v2/pages",

  		  CURLOPT_URL => $this->nessusUrl('/session'),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
    		"content-type: application/json",
		    $this->nessusApiHeader(),
		  ),
		  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
		  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
  		  CURLOPT_POSTFIELDS => json_encode([
              'username' => Yii::$app->params['nessusUsername'] ?? '',
              'password' => Yii::$app->params['nessusPassword'] ?? '',
          ]),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  //echo "cURL Error #:" . $err;
		  return 0;
		} else {
		  //echo $response;
		  return $response;
		}
    }

    public function actionKlasorac()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
  		  CURLOPT_URL => $this->nessusUrl('/folders'),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => array(
		    $this->nessusApiHeader(),
		    "accept: application/json",
    		"content-type: application/json",
		  ),
  		  CURLOPT_POSTFIELDS => "{\"name\":\"qwe\"}",
		  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
		  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
    }

    public function actionUuidal()
    {	    	
    		$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->nessusUrl('/editor/scan/templates'),
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    $this->nessusApiHeader(),
			    "accept: application/json"
			  ),
			  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
			  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			$response = json_decode($response); 
			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  //echo $response;
			echo "<pre>";print_r($response);	
			}
			//basit template
			//"uuid":"731a8e52-3ea6-a291-ec0a-d2ff0619c19d7bd788d6be818b65"
    }
	
	public function actionFolders()
	{
	    $curl = curl_init();

		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $this->nessusUrl('/folders'),
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			    $this->nessusApiHeader(),
			    "accept: application/json"
			  ),
			CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
			CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$response = json_decode($response); 
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo "<pre>";print_r($response);
		}
	}

    public function actionScanekle()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
  		  CURLOPT_URL => $this->nessusUrl('/scans'),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
  		  CURLOPT_POSTFIELDS => "{\"uuid\":\"731a8e52-3ea6-a291-ec0a-d2ff0619c19d7bd788d6be818b65\",\"settings\":{\"name\":\"denemescan\",\"enabled\":false,\"text_targets\":\"10.0.110.29\"}}",
  		  CURLOPT_HTTPHEADER => array(
		    $this->nessusApiHeader(),
		    "accept: application/json",
		    "content-type: application/json"
		  ),
		  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
		  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
    }

    public function actionListscan()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
  		  CURLOPT_URL => $this->nessusUrl('/scans?folder_id=3'),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
  		  CURLOPT_HTTPHEADER => array(
		    $this->nessusApiHeader(),
		    "accept: application/json",
		  ),
		  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
		  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$response = json_decode($response); 
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo "<pre>";print_r($response);
		}
    }

    public function actionScandevamet()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
  		  CURLOPT_URL => $this->nessusUrl('/scans/10/launch'),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
  		  CURLOPT_HTTPHEADER => array(
		    $this->nessusApiHeader(),
		    "accept: application/json",
    		"content-type: application/json"
		  ),
		  CURLOPT_SSL_VERIFYHOST => $this->nessusSslVerifyHost(),
		  CURLOPT_SSL_VERIFYPEER => $this->nessusSslVerifyPeer(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$response = json_decode($response); 
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo "<pre>";print_r($response);
		}
    }


}
