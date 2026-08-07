<?php

namespace app\behaviors;

use app\models\AuditLog;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class AuditLogBehavior extends Behavior
{
    public $excludedAttributes = [];
    private $oldAttributesOnDelete = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
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

    public function beforeDelete()
    {
        $this->oldAttributesOnDelete = $this->filteredAttributes($this->owner->getOldAttributes());
    }

    public function afterDelete(Event $event)
    {
        AuditLog::record($this->owner, 'delete', $this->oldAttributesOnDelete, null);
        $this->oldAttributesOnDelete = [];
    }

    private function filteredAttributes(array $attributes)
    {
        foreach ($this->excludedAttributes as $excludedAttribute) {
            unset($attributes[$excludedAttribute]);
        }

        return $attributes;
    }
}
