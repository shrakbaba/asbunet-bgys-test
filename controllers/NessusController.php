<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
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

    public function actionSessionac()
    {
        $curl = curl_init();

		curl_setopt_array($curl, array(
		  //CURLOPT_URL => "https://www.tenable.com/downloads/api/v2/pages",

  		  CURLOPT_URL => "https://10.0.110.30:8834/session",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
    		"content-type: application/json",
		    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
		  ),
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
  		  CURLOPT_POSTFIELDS => "{\"username\":\"alren\",\"password\":\"060117Ze.\"}",
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
  		  CURLOPT_URL => "https://10.0.110.30:8834/folders",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => array(
		    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
		    "accept: application/json",
    		"content-type: application/json",
		  ),
  		  CURLOPT_POSTFIELDS => "{\"name\":\"qwe\"}",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
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
			  CURLOPT_URL => "https://10.0.110.30:8834/editor/scan/templates",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
			    "accept: application/json"
			  ),
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
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
		  	CURLOPT_URL => "https://10.0.110.30:8834/folders",
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
			    "accept: application/json"
			  ),
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
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
  		  CURLOPT_URL => "https://10.0.110.30:8834/scans",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
  		  CURLOPT_POSTFIELDS => "{\"uuid\":\"731a8e52-3ea6-a291-ec0a-d2ff0619c19d7bd788d6be818b65\",\"settings\":{\"name\":\"denemescan\",\"enabled\":false,\"text_targets\":\"10.0.110.29\"}}",
  		  CURLOPT_HTTPHEADER => array(
		    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
		    "accept: application/json",
		    "content-type: application/json"
		  ),
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
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
  		  CURLOPT_URL => "https://10.0.110.30:8834/scans?folder_id=3",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
  		  CURLOPT_HTTPHEADER => array(
		    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
		    "accept: application/json",
		  ),
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
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
  		  CURLOPT_URL => "https://10.0.110.30:8834/scans/10/launch",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
  		  CURLOPT_HTTPHEADER => array(
		    "x-apikeys:accessKey=aa56bafb5d904ca6265a03fc8c4ca3d15a602fbbe6132c6eb92cbcaa5260e36a;secretKey=6da611d83f471288c08c9b962fbe29b5b42378f73da01bde30a8ff15621eebba",
		    "accept: application/json",
    		"content-type: application/json"
		  ),
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
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
