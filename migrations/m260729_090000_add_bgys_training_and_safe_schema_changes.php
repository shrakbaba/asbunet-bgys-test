<?php

use yii\db\Migration;

/**
 * Bu çalışma sırasında eklenen BGYS şema değişikliklerini güvenli şekilde tanımlar.
 *
 * Not:
 * - Canlı veritabanında tablo/kolon zaten varsa tekrar oluşturmaz.
 * - Test/başka ortama taşırken eksik tablo/kolonları tamamlar.
 * - Kod içinde kalıcı createTable/addColumn işlemi bırakmamak için eklendi.
 */
class m260729_090000_add_bgys_training_and_safe_schema_changes extends Migration
{
    public function safeUp()
    {
        $this->ensureFarkindalikEgitimTable();
        $this->ensureFarkindalikEgitimGirisTable();
        $this->ensureFarkindalikQuizChanges();
        $this->ensureRiskTrackingColumns();
    }

    public function safeDown()
    {
        echo "m260729_090000_add_bgys_training_and_safe_schema_changes veri kaybı riski nedeniyle otomatik geri alınamaz.\n";
        echo "Geri alma gerekiyorsa tablo/kolonlar ve canlı veri durumu ayrıca kontrol edilmelidir.\n";
        return false;
    }

    private function ensureFarkindalikEgitimTable()
    {
        $table = '{{%bgys_farkindalik_egitim}}';

        if (!$this->tableExists($table)) {
            $this->createTable($table, [
                'id' => $this->primaryKey(),
                'baslik' => $this->string(255)->notNull(),
                'aciklama' => $this->text()->null(),
                'video_dosya' => $this->string(255)->notNull(),
                'quiz_dosya' => $this->string(255)->null(),
                'quiz_orijinal_ad' => $this->string(255)->null(),
                'quiz_json' => $this->text()->null(),
                'aktif' => $this->boolean()->notNull()->defaultValue(1),
                'created_by' => $this->integer()->null(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);
            return;
        }

        $this->addColumnIfMissing($table, 'baslik', $this->string(255)->notNull());
        $this->addColumnIfMissing($table, 'aciklama', $this->text()->null());
        $this->addColumnIfMissing($table, 'video_dosya', $this->string(255)->notNull());
        $this->addColumnIfMissing($table, 'quiz_dosya', $this->string(255)->null());
        $this->addColumnIfMissing($table, 'quiz_orijinal_ad', $this->string(255)->null());
        $this->addColumnIfMissing($table, 'quiz_json', $this->text()->null());
        $this->addColumnIfMissing($table, 'aktif', $this->boolean()->notNull()->defaultValue(1));
        $this->addColumnIfMissing($table, 'created_by', $this->integer()->null());
        $this->addColumnIfMissing($table, 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    private function ensureFarkindalikEgitimGirisTable()
    {
        $table = '{{%bgys_farkindalik_egitim_giris}}';

        if (!$this->tableExists($table)) {
            $this->createTable($table, [
                'id' => $this->primaryKey(),
                'egitim_id' => $this->integer()->notNull(),
                'kullanici' => $this->string(255)->notNull(),
                'giris_tarihi' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);
        } else {
            $this->addColumnIfMissing($table, 'egitim_id', $this->integer()->notNull());
            $this->addColumnIfMissing($table, 'kullanici', $this->string(255)->notNull());
            $this->addColumnIfMissing($table, 'giris_tarihi', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
        }

        $this->createIndexIfMissing(
            'idx-bgys_farkindalik_egitim_giris-tekil',
            $table,
            ['egitim_id', 'kullanici'],
            true
        );
    }

    private function ensureFarkindalikQuizChanges()
    {
        $table = '{{%bgys_farkindalik_quiz}}';

        if (!$this->tableExists($table)) {
            $this->createTable($table, [
                'id' => $this->primaryKey(),
                'egitim_id' => $this->integer()->null(),
                'cevaplayan' => $this->string(255)->notNull(),
                'ip' => $this->string(255)->notNull(),
                'cevaplamatarihi' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'cevaplar' => $this->string(750)->notNull(),
                'puan' => $this->string(255)->null(),
            ]);
        } else {
            $this->addColumnIfMissing($table, 'egitim_id', $this->integer()->null());
            $this->addColumnIfMissing($table, 'cevaplar', $this->string(750)->notNull());

            if ($this->columnExists($table, 'cevaplar')) {
                $this->alterColumn($table, 'cevaplar', $this->string(750)->notNull());
            }
        }

        $this->createIndexIfMissing('idx-bgys_farkindalik_quiz-egitim_id', $table, 'egitim_id');
    }

    private function ensureRiskTrackingColumns()
    {
        $table = '{{%bgys_risk}}';

        if (!$this->tableExists($table)) {
            return;
        }

        $this->addColumnIfMissing($table, 'updated_at', $this->timestamp()->null());
        $this->addColumnIfMissing($table, 'updated_by', $this->integer()->null());
        $this->addColumnIfMissing($table, 'aktif', $this->boolean()->null());
        $this->addColumnIfMissing($table, 'pasif_aciklama', $this->text()->null());
    }

    private function addColumnIfMissing($table, $column, $type)
    {
        if (!$this->columnExists($table, $column)) {
            $this->addColumn($table, $column, $type);
        }
    }

    private function createIndexIfMissing($name, $table, $columns, $unique = false)
    {
        if (!$this->indexExists($table, $name)) {
            $this->createIndex($name, $table, $columns, $unique);
        }
    }

    private function tableExists($table)
    {
        return $this->db->schema->getTableSchema($this->db->schema->getRawTableName($table), true) !== null;
    }

    private function columnExists($table, $column)
    {
        $schema = $this->db->schema->getTableSchema($this->db->schema->getRawTableName($table), true);
        return $schema !== null && isset($schema->columns[$column]);
    }

    private function indexExists($table, $indexName)
    {
        $rawTable = $this->db->schema->getRawTableName($table);
        $indexes = $this->db->createCommand("SHOW INDEX FROM `{$rawTable}`")->queryAll();
        foreach ($indexes as $index) {
            if (($index['Key_name'] ?? null) === $indexName) {
                return true;
            }
        }
        return false;
    }
}
