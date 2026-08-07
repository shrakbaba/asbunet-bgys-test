<?php

namespace app\services;

use app\models\Action;

class ActionWorkflowService
{
    public function validateClose(Action $action)
    {
        $errors = [];

        $evidenceCount = (int) $action->getEvidences()->count();
        if ($evidenceCount < 1) {
            $errors[] = 'Aksiyon kapatmak için en az bir kanıt eklenmelidir.';
        }

        $approvalCount = (int) $action->getActionApprovals()->count();

        if ($approvalCount < 1) {
            $errors[] = 'Aksiyon kapatmak için reviewer ve approver kaydı bulunmalıdır.';
        }

        return $errors;
    }
}
