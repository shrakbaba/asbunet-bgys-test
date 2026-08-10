<?php

namespace app\controllers;

use Yii;
use app\components\SecureFileStorage;
use app\models\Bgysfarkindalikegitim;
use app\models\Bgysfarkindalikquiz;
use app\models\BgysfarkindalikquizSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

use yii\filters\AccessControl;
use yii\helpers\bgys;
use yii\web\UploadedFile;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'egitimsil' => ['POST'],
                    'egitimaktifpasif' => ['POST'],
                    'egitimtamamlandi' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','delete'],
                        'roles' => ['BGYS_Super_Admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','egitim','egitimtamamlandi','quizdokuman','video','quizfile'],
                        'roles' => ['BGYS_Ekip_Uyesi'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['egitimekle','egitimguncelle','egitimsil','egitimaktifpasif','egitimgoster'],
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
        $searchModel = new BgysfarkindalikquizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionEgitim($id = null)
    {
        $egitimSorgu = Bgysfarkindalikegitim::find()
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);
        if (!Yii::$app->user->can('BGYS_Super_Admin')) {
            $egitimSorgu->where(['aktif' => 1]);
        }
        $egitimler = $egitimSorgu->all();

        $egitim = null;
        if ($id) {
            $egitimSorgu = Bgysfarkindalikegitim::find()->where(['id' => $id]);
            if (!Yii::$app->user->can('BGYS_Super_Admin')) {
                $egitimSorgu->andWhere(['aktif' => 1]);
            }
            $egitim = $egitimSorgu->one();
        }
        if ($egitim === null && $egitimler) {
            $egitim = $egitimler[0];
        }

        $cevaplayan = $this->cevaplayanBilgisi();
        $egitimIdleri = array_map(function ($egitimKaydi) {
            return (int)$egitimKaydi->id;
        }, $egitimler);
        $cozulenEgitimIdleri = $egitimIdleri
            ? Bgysfarkindalikquiz::find()
                ->select('egitim_id')
                ->where(['cevaplayan' => $cevaplayan, 'egitim_id' => $egitimIdleri])
                ->column()
            : [];
        $izlenenEgitimIdleri = $egitimIdleri
            ? (new \yii\db\Query())
                ->select('egitim_id')
                ->from('bgys_farkindalik_egitim_giris')
                ->where(['kullanici' => $cevaplayan, 'egitim_id' => $egitimIdleri])
                ->column()
            : [];
        $a = $egitim
            ? (Bgysfarkindalikquiz::find()->where(['cevaplayan' => $cevaplayan, 'egitim_id' => $egitim->id])->exists() ? 1 : 0)
            : 0;
        $egitimiIzledi = $egitim
            ? (new \yii\db\Query())
                ->from('bgys_farkindalik_egitim_giris')
                ->where(['egitim_id' => $egitim->id, 'kullanici' => $cevaplayan])
                ->exists()
            : false;

        return $this->render('egitim',[
            'a'=>$a,
            'egitimler' => $egitimler,
            'egitim' => $egitim,
            'egitimiIzledi' => $egitimiIzledi,
            'cozulenEgitimIdleri' => array_map('intval', $cozulenEgitimIdleri),
            'izlenenEgitimIdleri' => array_map('intval', $izlenenEgitimIdleri),
        ]);
        
    }

    public function actionView($id)
    {
        //var_dump((new Bgysfarkindalikquiz())->dogrular);exit;
        $render = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$render('view', [
            'model' => $this->findModel($id),
            //'dogrular' => $a,
        ]);
    }

    public function actionCreate()
    {
        $cevaplayan = $this->cevaplayanBilgisi();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Bgysfarkindalikquiz', []);
            $egitimId = $post['egitim_id'] ?? null;
            $model = Bgysfarkindalikquiz::find()
                ->where(['cevaplayan' => $cevaplayan, 'egitim_id' => $egitimId])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($model === null) {
                $model = new Bgysfarkindalikquiz();
            }

            $model->load(Yii::$app->request->post());
            $sended = $post;
            $model->ip=Yii::$app->request->userIP;
            $model->cevaplayan = $cevaplayan;
            $egitim = Bgysfarkindalikegitim::findOne($model->egitim_id);
            if ($egitim === null) {
                Yii::$app->session->setFlash('error','Eğitim kaydı bulunamadı.');
                return $this->redirect(['egitim']);
            }
            $model->populateRelation('egitim', $egitim);
            if (count($model->quizSorulari) === 0) {
                Yii::$app->session->setFlash('error','Bu eğitim için quiz soruları henüz tanımlanmamış.');
                return $this->redirect(['egitim', 'id' => $model->egitim_id]);
            }

            $cevaplar=[];
            foreach ($model->quizSorulari as $index => $soru) {
                $alan = 'soru' . ($index + 1);
                $cevaplar[$alan] = $sended[$alan] ?? null;
            }
            $model->cevaplar=json_encode($cevaplar);

        $d=0;
        foreach ($cevaplar as $index => $value) {
            if ($value!=null) {
                $soruIndex = ((int)str_replace('soru', '', $index)) - 1;
                $dogru = $model->quizSorulari[$soruIndex]['dogru'] ?? null;
                if ($dogru !== null && (int)$dogru==intval($value)) {
                    $d++; //doğru cevap sayısı
                }
            }
        }

        $soruSayisi = count($model->quizSorulari);
        $model->puan=strval($soruSayisi ? round(((100/$soruSayisi)*$d),2) : 0);
        $model->cevaplamatarihi = Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s');
            if($model->save()) {
                Bgysfarkindalikquiz::deleteAll(
                    'cevaplayan = :cevaplayan AND egitim_id = :egitim_id AND id <> :id',
                    [
                        ':cevaplayan' => $model->cevaplayan,
                        ':egitim_id' => $model->egitim_id,
                        ':id' => $model->id,
                    ]
                );
                $userId = Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->id;
                bgys::logtut(Yii::$app->controller->id,Yii::$app->controller->action->id,$userId,'farkindalik quiz tanimlama','farkindalik:'.$model->cevaplayan);
                Yii::$app->session->setFlash('success','Sonuçlarınız başarıyla alındı. Teşekkürler.');
                return $this->redirect(['egitim', 'id' => $model->egitim_id]);
            }

            Yii::$app->session->setFlash('error','Kayıt sırasında hata oluştu.');
            return $this->redirect(['egitim', 'id' => $model->egitim_id]);
        }

        $egitimId = Yii::$app->request->get('egitim_id');
        $egitim = Bgysfarkindalikegitim::findOne($egitimId);
        if ($egitim === null) {
            throw new NotFoundHttpException('Eğitim kaydı bulunamadı.');
        }
        $mevcutSonuc = Bgysfarkindalikquiz::find()
            ->where(['cevaplayan' => $cevaplayan, 'egitim_id' => $egitim->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if ($mevcutSonuc && !Yii::$app->request->get('yeniden')) {
            return $this->renderAjax('cevaplar', [
                'model' => $mevcutSonuc,
            ]);
        }

        $model = $mevcutSonuc ?: new Bgysfarkindalikquiz();
        $model->egitim_id = $egitim->id;
        $model->cevaplayan = $cevaplayan;
        $model->ip = Yii::$app->request->userIP;
        $model->populateRelation('egitim', $egitim);
        foreach ($model->quizSorulari as $index => $soru) {
            $model->{'soru' . ($index + 1)} = null;
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionEgitimekle()
    {
        $model = new Bgysfarkindalikegitim();
        $model->aktif = 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->videoFile = UploadedFile::getInstance($model, 'videoFile');
            $model->quizFile = UploadedFile::getInstance($model, 'quizFile');
            $this->quizFormunuModeleAktar($model);
            if ($model->videoFile === null) {
                $model->addError('videoFile', 'Eğitim videosu yüklenmelidir.');
            } elseif ($this->egitimKaydet($model)) {
                Yii::$app->session->setFlash('success','Eğitim videosu ve quiz kaydı eklendi.');
                return '<script>window.location.reload();</script>';
            }
        }

        return $this->renderAjax('egitimform', [
            'model' => $model,
        ]);
    }

    public function actionEgitimguncelle($id)
    {
        $model = $this->findEgitimModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->videoFile = UploadedFile::getInstance($model, 'videoFile');
            $model->quizFile = UploadedFile::getInstance($model, 'quizFile');
            $this->quizFormunuModeleAktar($model);
            if ($this->egitimKaydet($model)) {
                Yii::$app->session->setFlash('success','Eğitim kaydı güncellendi.');
                return '<script>window.location.reload();</script>';
            }
        }

        return $this->renderAjax('egitimform', [
            'model' => $model,
        ]);
    }

    public function actionEgitimgoster($id)
    {
        return $this->renderAjax('egitimgoster', [
            'model' => $this->findEgitimModel($id),
        ]);
    }

    public function actionEgitimaktifpasif($id)
    {
        $model = $this->findEgitimModel($id);
        $model->aktif = $model->aktif ? 0 : 1;
        $model->save(false);
        Yii::$app->session->setFlash('success', $model->aktif ? 'Eğitim aktif edildi.' : 'Eğitim pasife alındı.');
        return $this->redirect(['egitim', 'id' => $model->id]);
    }

    public function actionEgitimtamamlandi($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findEgitimModel($id);
        $cevaplayan = $this->cevaplayanBilgisi();

        try {
            Yii::$app->db->createCommand()->insert('bgys_farkindalik_egitim_giris', [
                'egitim_id' => $model->id,
                'kullanici' => $cevaplayan,
            ])->execute();
        } catch (\Throwable $e) {
            // Kullanıcı bu eğitimi daha önce tamamladıysa tekil kayıt nedeniyle tekrar eklemeyiz.
        }

        return ['success' => true];
    }

    public function actionQuizdokuman($id)
    {
        $model = $this->findAccessibleEgitimModel($id);
        $sorular = $model->quiz_json ? json_decode($model->quiz_json, true) : [];

        if (!is_array($sorular) || count($sorular) === 0) {
            throw new NotFoundHttpException('Bu eğitim için quiz sorusu tanımlanmamış.');
        }

        $html = '<h2>' . htmlspecialchars($model->baslik, ENT_QUOTES, 'UTF-8') . ' - Quiz Soruları</h2>';
        $html .= '<p>Bu dokümanda sadece sorular ve seçenekler yer alır. Cevap anahtarı gösterilmez.</p>';
        foreach ($sorular as $index => $soru) {
            $html .= '<div style="margin-bottom:14px;">';
            $html .= '<strong>' . ((int)$index + 1) . '. ' . htmlspecialchars($soru['soru'] ?? '', ENT_QUOTES, 'UTF-8') . '</strong>';
            $html .= '<ol type="A">';
            foreach ((array)($soru['secenekler'] ?? []) as $secenek) {
                $html .= '<li>' . htmlspecialchars($secenek, ENT_QUOTES, 'UTF-8') . '</li>';
            }
            $html .= '</ol>';
            $html .= '</div>';
        }

        $tempDir = Yii::$app->runtimePath . '/mpdf';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'UTF-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'tempDir' => $tempDir,
        ]);
        $mpdf->WriteHTML('<style>body{font-family:dejavusans;font-size:11pt;} h2{color:#7b1b42;}</style>');
        $mpdf->WriteHTML($html);

        $filename = preg_replace('/[^A-Za-z0-9_.-]/', '_', $model->baslik) . '_quiz.pdf';
        $content = $mpdf->Output('', 'S');
        return Yii::$app->response->sendContentAsFile($content, $filename, [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionVideo($id)
    {
        $model = $this->findAccessibleEgitimModel($id);
        if (!$model->video_dosya) {
            throw new NotFoundHttpException('Eğitim videosu bulunamadı.');
        }
        $path = SecureFileStorage::find($model->video_dosya, 'training-videos', [$this->legacyVideoDirectory()]);
        bgys::logtut($this->id, $this->action->id, Yii::$app->user->id, 'eğitim videosu görüntülendi', 'eğitim:' . $model->id);
        return Yii::$app->response->sendFile($path, 'egitim-video-' . $model->id . '.mp4', [
            'mimeType' => 'video/mp4',
            'inline' => true,
        ]);
    }

    public function actionQuizfile($id)
    {
        $model = $this->findAccessibleEgitimModel($id);
        if (!$model->quiz_dosya) {
            throw new NotFoundHttpException('Quiz dosyası bulunamadı.');
        }
        $path = SecureFileStorage::find($model->quiz_dosya, 'training-documents', [$this->legacyQuizDirectory()]);
        $name = basename(str_replace(["\r", "\n"], '', (string)$model->quiz_orijinal_ad));
        bgys::logtut($this->id, $this->action->id, Yii::$app->user->id, 'eğitim quiz belgesi görüntülendi', 'eğitim:' . $model->id);
        return Yii::$app->response->sendFile($path, $name ?: 'egitim-quiz-' . $model->id . '.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionEgitimsil($id)
    {
        $model = $this->findEgitimModel($id);
        $model->aktif = 0;
        $model->save(false);
        Yii::$app->session->setFlash('success','Eğitim pasife alındı.');
        return $this->redirect(['egitim']);
    }

    private function cevaplayanBilgisi()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->request->userIP;
        }

        return Yii::$app->user->identity->username ?: (string) Yii::$app->user->identity->id;
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

    protected function findEgitimModel($id)
    {
        if (($model = Bgysfarkindalikegitim::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Eğitim kaydı bulunamadı.');
    }

    protected function findAccessibleEgitimModel($id)
    {
        $query = Bgysfarkindalikegitim::find()->where(['id' => $id]);
        if (!Yii::$app->user->can('BGYS_Super_Admin')) {
            $query->andWhere(['aktif' => 1]);
        }
        $model = $query->one();
        if ($model === null) {
            throw new NotFoundHttpException('Eğitim kaydı bulunamadı.');
        }
        return $model;
    }

    private function egitimKaydet(Bgysfarkindalikegitim $model)
    {
        if (!$model->video_dosya && !$model->videoFile) {
            $model->addError('videoFile', 'Eğitim videosu yüklenmelidir.');
            return false;
        }
        if ($model->hasErrors()) {
            return false;
        }

        if (!$model->created_by && !Yii::$app->user->isGuest) {
            $model->created_by = Yii::$app->user->identity->id;
        }

        if (!$model->validate()) {
            return false;
        }

        $oldVideo = $model->getOldAttribute('video_dosya');
        $oldQuiz = $model->getOldAttribute('quiz_dosya');
        $newFiles = [];
        try {
            if ($model->videoFile) {
                $model->video_dosya = SecureFileStorage::store($model->videoFile, 'training-videos', 'mp4');
                $newFiles[] = ['name' => $model->video_dosya, 'category' => 'training-videos'];
            }
            if ($model->quizFile) {
                $model->quiz_dosya = SecureFileStorage::storePdf($model->quizFile, 'training-documents');
                $model->quiz_orijinal_ad = $model->quizFile->name;
                $newFiles[] = ['name' => $model->quiz_dosya, 'category' => 'training-documents'];
            }
            if (!$model->save(false)) {
                throw new \RuntimeException('Eğitim kaydı kaydedilemedi.');
            }
        } catch (\Throwable $exception) {
            foreach ($newFiles as $newFile) {
                SecureFileStorage::delete($newFile['name'], $newFile['category']);
            }
            Yii::error($exception, 'training-file');
            $model->addError('videoFile', 'Eğitim dosyaları kaydedilemedi.');
            return false;
        }

        if ($oldVideo && $oldVideo !== $model->video_dosya) {
            SecureFileStorage::delete($oldVideo, 'training-videos', [$this->legacyVideoDirectory()]);
        }
        if ($oldQuiz && $oldQuiz !== $model->quiz_dosya) {
            SecureFileStorage::delete($oldQuiz, 'training-documents', [$this->legacyQuizDirectory()]);
        }
        return true;
    }

    private function legacyVideoDirectory()
    {
        return Yii::$app->basePath . '/web/uploads/bgys/egitim';
    }

    private function legacyQuizDirectory()
    {
        return Yii::$app->basePath . '/web/uploads/bgys/egitim/quiz';
    }

    private function quizFormunuModeleAktar(Bgysfarkindalikegitim $model)
    {
        $quizPost = Yii::$app->request->post('Quiz', []);
        $sorular = [];

        foreach ((array)$quizPost as $quizSatiri) {
            $soruMetni = trim($quizSatiri['soru'] ?? '');
            $secenekler = [];
            foreach ((array)($quizSatiri['secenekler'] ?? []) as $secenek) {
                $secenekler[] = trim($secenek);
            }
            $dogru = $quizSatiri['dogru'] ?? null;

            if ($soruMetni === '' && implode('', $secenekler) === '') {
                continue;
            }

            $secenekler = array_values(array_filter($secenekler, function ($secenek) {
                return $secenek !== '';
            }));

            if ($soruMetni === '' || count($secenekler) < 2 || $dogru === null || !isset($secenekler[(int)$dogru])) {
                $model->addError('quiz_json', 'Her quiz sorusunda soru metni, en az iki şık ve doğru cevap seçimi olmalıdır.');
                continue;
            }

            $sorular[] = [
                'soru' => $soruMetni,
                'secenekler' => $secenekler,
                'dogru' => (int)$dogru,
            ];
        }

        $model->quiz_json = $sorular
            ? json_encode($sorular, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            : null;
    }
}
