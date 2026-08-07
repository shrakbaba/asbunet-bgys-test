<?php

namespace app\behaviors;

use app\models\AuditLog;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class AuditLogBehavior extends Behavior
{
    public $excludedAttributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterInsert(AfterSaveEvent $event)
    {
        AuditLog::record($this->owner, 'create', null, $this->filteredAttributes($this->owner->getAttributes()));
    }

    public function afterUpdate(AfterSaveEvent $event)
    {
        $oldValues = $this->filteredAttributes($event->changedAttributes);
        $newValues = [];
        foreach (array_keys($oldValues) as $attribute) {
            $newValues[$attribute] = $this->owner->$attribute;
        }

        AuditLog::record($this->owner, 'update', $oldValues, $newValues);
    }

    public function afterDelete()
    {
        AuditLog::record($this->owner, 'delete', $this->filteredAttributes($this->owner->getOldAttributes()), null);
    }

    private function filteredAttributes(array $attributes)
    {
        foreach ($this->excludedAttributes as $excludedAttribute) {
            unset($attributes[$excludedAttribute]);
        }

        return $attributes;
    }
}
