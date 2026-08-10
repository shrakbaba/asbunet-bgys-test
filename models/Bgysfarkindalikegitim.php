<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;

class Bgysfarkindalikegitim extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $videoFile;
    /** @var UploadedFile */
    public $quizFile;

    public static function tableName()
    {
        return 'bgys_farkindalik_egitim';
    }

    public function rules()
    {
        return [
            [['baslik'], 'required'],
            [['aciklama', 'quiz_json'], 'string'],
            [['aktif', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['baslik', 'video_dosya', 'quiz_dosya', 'quiz_orijinal_ad'], 'string', 'max' => 255],
            [['videoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['mp4'], 'mimeTypes' => ['video/mp4'], 'maxSize' => 1024 * 1024 * 500],
            [['quizFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf'], 'mimeTypes' => ['application/pdf'], 'maxSize' => 1024 * 1024 * 20],
            [['quiz_json'], 'validateQuizJson'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'baslik' => 'Eğitim Başlığı',
            'aciklama' => 'Açıklama',
            'video_dosya' => 'Video Dosyası',
            'videoFile' => 'Eğitim Videosu (MP4)',
            'quiz_dosya' => 'Quiz Dosyası',
            'quiz_orijinal_ad' => 'Quiz Dosyası',
            'quizFile' => 'Quiz Dosyası (PDF)',
            'quiz_json' => 'Quiz Soruları',
            'aktif' => 'Aktif',
            'created_by' => 'Ekleyen',
            'created_at' => 'Eklenme Tarihi',
        ];
    }

    public function validateQuizJson($attribute)
    {
        if (!$this->$attribute) {
            return;
        }

        $quiz = json_decode($this->$attribute, true);
        if (!is_array($quiz)) {
            $this->addError($attribute, 'Quiz soruları geçerli JSON formatında olmalıdır.');
            return;
        }

        foreach ($quiz as $index => $soru) {
            if (empty($soru['soru']) || empty($soru['secenekler']) || !is_array($soru['secenekler']) || !array_key_exists('dogru', $soru)) {
                $this->addError($attribute, ($index + 1) . '. soru için soru/secenekler/dogru alanları zorunludur.');
                return;
            }
        }
    }

    public function getQuizler()
    {
        return $this->hasMany(Bgysfarkindalikquiz::className(), ['egitim_id' => 'id']);
    }

    public function getVideoUrl()
    {
        return Url::to(['/bgysfarkindalikquiz/video', 'id' => $this->id]);
    }

    public function getQuizUrl()
    {
        return $this->quiz_dosya ? Url::to(['/bgysfarkindalikquiz/quizfile', 'id' => $this->id]) : null;
    }

    public static function varsayilanQuizJson()
    {
        return json_encode((new Bgysfarkindalikquiz())->varsayilanQuizSorulari(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
