<?php

use yii\db\Migration;
use yii\db\Query;

class m260810_160000_add_legacy_integrity_constraints extends Migration
{
    private const RELATIONS = [
        ['bgys_farkindalik_egitim', 'created_by', 'user', 'id', 'RESTRICT'],
        ['bgys_farkindalik_egitim_giris', 'egitim_id', 'bgys_farkindalik_egitim', 'id', 'RESTRICT'],
        ['bgys_farkindalik_quiz', 'egitim_id', 'bgys_farkindalik_egitim', 'id', 'RESTRICT'],
        ['bgys_olay_kayit', 'userid', 'user', 'id', 'RESTRICT'],
        ['bgys_olay_kayit_belge', 'olay_id', 'bgys_olay_kayit', 'id', 'CASCADE'],
        ['bgys_olay_kayit_belge', 'created_by', 'user', 'id', 'RESTRICT'],
    ];

    public function safeUp()
    {
        foreach (self::RELATIONS as $relation) {
            [$childTable, $childColumn, $parentTable, $parentColumn, $deleteRule] = $relation;
            $this->assertNoOrphans($childTable, $childColumn, $parentTable, $parentColumn);
        }

        foreach (self::RELATIONS as $relation) {
            [$childTable, $childColumn, $parentTable, $parentColumn, $deleteRule] = $relation;
            $this->ensureIndex($childTable, $childColumn);
            $this->addForeignKey(
                $this->foreignKeyName($childTable, $childColumn),
                '{{%' . $childTable . '}}',
                $childColumn,
                '{{%' . $parentTable . '}}',
                $parentColumn,
                $deleteRule,
                'CASCADE'
            );
        }
    }

    public function safeDown()
    {
        foreach (array_reverse(self::RELATIONS) as $relation) {
            [$childTable, $childColumn] = $relation;
            $this->dropForeignKey(
                $this->foreignKeyName($childTable, $childColumn),
                '{{%' . $childTable . '}}'
            );
        }

        foreach ($this->indexesCreatedByMigration() as $index) {
            $this->dropIndex($index[0], '{{%' . $index[1] . '}}');
        }
    }

    private function assertNoOrphans($childTable, $childColumn, $parentTable, $parentColumn)
    {
        $child = $this->db->quoteTableName($childTable);
        $parent = $this->db->quoteTableName($parentTable);
        $childKey = $this->db->quoteColumnName($childColumn);
        $parentKey = $this->db->quoteColumnName($parentColumn);
        $count = (new Query())
            ->from(['child' => $childTable])
            ->leftJoin(['parent' => $parentTable], 'parent.' . $parentKey . ' = child.' . $childKey)
            ->where(['not', ['child.' . $childColumn => null]])
            ->andWhere(['parent.' . $parentColumn => null])
            ->count('*', $this->db);

        if ((int)$count > 0) {
            throw new RuntimeException("Sahipsiz kayıt bulundu: {$child}.{$childKey} -> {$parent}.{$parentKey} ({$count})");
        }
    }

    private function ensureIndex($table, $column)
    {
        foreach ($this->db->schema->getTableIndexes($table, true) as $index) {
            if (isset($index->columnNames[0]) && $index->columnNames[0] === $column) {
                return;
            }
        }

        $name = $this->indexName($table, $column);
        $this->createIndex($name, '{{%' . $table . '}}', $column);
    }

    private function indexesCreatedByMigration()
    {
        return [
            [$this->indexName('bgys_farkindalik_egitim', 'created_by'), 'bgys_farkindalik_egitim'],
            [$this->indexName('bgys_logs', 'userid'), 'bgys_logs'],
            [$this->indexName('bgys_olay_kayit', 'userid'), 'bgys_olay_kayit'],
            [$this->indexName('bgys_olay_kayit_belge', 'created_by'), 'bgys_olay_kayit_belge'],
        ];
    }

    private function foreignKeyName($table, $column)
    {
        return 'fk-' . $table . '-' . $column;
    }

    private function indexName($table, $column)
    {
        return 'idx-' . $table . '-' . $column;
    }
}
