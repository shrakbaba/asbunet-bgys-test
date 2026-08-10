<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Bgyslogs;
use app\models\BgyslogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\bgys;
use yii\helpers\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * BgyslogsController implements the CRUD actions for Bgyslogs model.
 */
class BgyslogsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','export-excel','export-pdf'],
                        'roles' => ['BGYS_Super_Admin'],
                    ],
                   /* [
                      'allow' => false,
                      'roles' => ['@'],
                      'denyCallback' => function($rule, $action) {
                         Yii::$app->session->setFlash('error', 'Hatalı işlem.');
                         Yii::$app->user->loginRequired();
                       }
                    ]*/
                ],
            ],
        ];
    }

    /**
     * Lists all Bgyslogs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BgyslogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bgyslogs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        bgys::logtut($this->id, $this->action->id, Yii::$app->user->id, 'audit kaydı görüntülendi', 'audit:' . $model->id, [
            'record_type' => 'audit_log',
            'record_id' => $model->id,
        ]);
        $params = [
            'model' => $model,
        ];

        return Yii::$app->request->isAjax
            ? $this->renderAjax('view', $params)
            : $this->render('view', $params);
    }

    public function actionExportExcel()
    {
        $rows = $this->auditReportRows();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Audit Kayıtları');
        $headers = [
            'ID', 'Tarih', 'Kullanıcı', 'Roller', 'Controller', 'Action', 'İşlem', 'Not',
            'Sonuç', 'IP Adresi', 'User-Agent', 'Correlation ID', 'Kayıt Türü', 'Kayıt ID',
            'Önceki Değerler', 'Yeni Değerler',
        ];
        $sheet->fromArray($headers, null, 'A1');

        $rowNumber = 2;
        foreach ($rows as $row) {
            $sheet->fromArray([
                $row->id, $row->date, $row->actor, $row->role, $row->controller, $row->action,
                $row->islem, $row->not, $row->result, $row->ip_address, $row->user_agent,
                $row->correlation_id, $row->record_type, $row->record_id, $row->old_values, $row->new_values,
            ], null, 'A' . $rowNumber++);
        }
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:P' . max(1, $rowNumber - 1));
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        $fileName = 'bgys-audit-' . date('Ymd-His') . '.xlsx';
        $path = Yii::$app->runtimePath . DIRECTORY_SEPARATOR . Yii::$app->security->generateRandomString(24) . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $this->logExport('excel', count($rows));
        return Yii::$app->response->sendFile($path, $fileName, [
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->on(\yii\web\Response::EVENT_AFTER_SEND, function () use ($path) {
            if (is_file($path)) {
                unlink($path);
            }
        });
    }

    public function actionExportPdf()
    {
        $rows = $this->auditReportRows();
        $tempDir = Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'mpdf';
        if (!is_dir($tempDir) && !mkdir($tempDir, 0770, true) && !is_dir($tempDir)) {
            throw new \RuntimeException('PDF geçici dizini oluşturulamadı.');
        }
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'tempDir' => $tempDir,
        ]);
        $html = '<h2>BGYS Audit Kayıtları</h2><p>Oluşturma: ' . Html::encode(date('d.m.Y H:i:s'))
            . ' | Kayıt sayısı: ' . count($rows) . '</p>'
            . '<table><thead><tr><th>ID</th><th>Tarih</th><th>Kullanıcı</th><th>İşlem</th>'
            . '<th>Sonuç</th><th>IP</th><th>Kayıt</th></tr></thead><tbody>';
        foreach ($rows as $row) {
            $html .= '<tr><td>' . (int)$row->id . '</td><td>' . Html::encode($row->date) . '</td><td>'
                . Html::encode($row->actor) . '</td><td>' . Html::encode($row->controller . '/' . $row->action . ' - ' . $row->islem)
                . '</td><td>' . Html::encode($row->result) . '</td><td>' . Html::encode($row->ip_address)
                . '</td><td>' . Html::encode(trim($row->record_type . ':' . $row->record_id, ':')) . '</td></tr>';
        }
        $html .= '</tbody></table>';
        $mpdf->WriteHTML('<style>body{font-family:dejavusans;font-size:8pt}table{border-collapse:collapse;width:100%}'
            . 'th,td{border:1px solid #aaa;padding:4px;vertical-align:top}th{background:#eee}</style>');
        $mpdf->WriteHTML($html);
        $content = $mpdf->Output('', 'S');

        $this->logExport('pdf', count($rows));
        return Yii::$app->response->sendContentAsFile($content, 'bgys-audit-' . date('Ymd-His') . '.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => false,
        ]);
    }

    private function auditReportRows()
    {
        $searchModel = new BgyslogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);
        return $dataProvider->getModels();
    }

    private function logExport($format, $recordCount)
    {
        bgys::logtut($this->id, $this->action->id, Yii::$app->user->id, 'audit raporu dışa aktarıldı', 'format:' . $format . ';kayıt:' . $recordCount, [
            'record_type' => 'audit_report',
            'record_id' => (string)$recordCount,
            'new_values' => ['format' => $format, 'filters' => Yii::$app->request->queryParams],
        ]);
    }

    /**
     * Finds the Bgyslogs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bgyslogs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bgyslogs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
