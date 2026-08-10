<?php

use yii\db\Migration;
use yii\db\Query;

class m260810_161000_convert_audit_log_to_innodb extends Migration
{
    public function safeUp()
    {
        $orphanCount = (new Query())
            ->from(['log' => '{{%bgys_logs}}'])
            ->leftJoin(['user' => '{{%user}}'], 'user.id = log.userid')
            ->where(['user.id' => null])
            ->count('*', $this->db);
        if ((int)$orphanCount > 0) {
            throw new RuntimeException("Kullanıcısı bulunmayan audit kaydı var: {$orphanCount}");
        }

        $this->execute('ALTER TABLE {{%bgys_logs}} ENGINE=InnoDB');
        $this->addForeignKey(
            'fk-bgys_logs-userid',
            '{{%bgys_logs}}',
            'userid',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-bgys_logs-userid', '{{%bgys_logs}}');
        $this->execute('ALTER TABLE {{%bgys_logs}} ENGINE=MyISAM');
    }
}
