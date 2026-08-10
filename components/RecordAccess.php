<?php

namespace app\components;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

final class RecordAccess
{
    private const PRIVILEGED_ROLES = [
        'BGYS_Ekip_Lideri',
        'BGYS_Yonetim_Temsilcisi',
        'BGYS_Super_Admin',
    ];

    public static function canManage($record, array $ownerAttributes): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        foreach (self::PRIVILEGED_ROLES as $role) {
            if (Yii::$app->user->can($role)) {
                return true;
            }
        }

        $userId = (int)Yii::$app->user->id;
        foreach ($ownerAttributes as $attribute) {
            if ($record->hasAttribute($attribute) && (int)$record->getAttribute($attribute) === $userId) {
                return true;
            }
        }

        return false;
    }

    public static function assertCanManage($record, array $ownerAttributes, string $resource): void
    {
        if (!is_object($record)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (self::canManage($record, $ownerAttributes)) {
            return;
        }

        Yii::warning([
            'message' => 'Kayit yonetim yetkisi reddedildi.',
            'resource' => $resource,
            'recordId' => $record->getPrimaryKey(),
            'userId' => Yii::$app->user->isGuest ? null : Yii::$app->user->id,
        ], 'security.authorization');

        throw new ForbiddenHttpException('Bu kaydi degistirme yetkiniz bulunmuyor.');
    }

    public static function hasDirectRole(string $role): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return Yii::$app->authManager->getAssignment($role, (string)Yii::$app->user->id) !== null;
    }
}
