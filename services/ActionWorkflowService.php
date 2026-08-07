<?php

namespace app\services;

use app\models\Action;

class ActionWorkflowService
{
    public function validateClose(Action $action)
    {
        $errors = [];

        if ($action->getIsNewRecord()) {
            $errors[] = 'Aksiyon kaydı oluşturulmadan kapatılamaz.';
            return $errors;
        }

        $evidenceCount = (int) $action->getEvidences()->count();
        if ($evidenceCount < 1) {
            $errors[] = 'Aksiyon kapatmak için en az bir kanıt eklenmelidir.';
        }

        $approvalCount = (int) $action->getActionApprovals()
            ->andWhere(['not', ['reviewer_id' => null]])
            ->andWhere(['not', ['approver_id' => null]])
            ->count();

        if ($approvalCount < 1) {
            $errors[] = 'Aksiyon kapatmak için reviewer ve approver kaydı bulunmalıdır.';
        }

        return $errors;
    }
}
