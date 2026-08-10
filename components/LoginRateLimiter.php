<?php

namespace app\components;

use app\models\Bgyslogs;

class LoginRateLimiter
{
    private $maxAttempts;
    private $windowSeconds;

    public function __construct($maxAttempts, $windowSeconds)
    {
        $this->maxAttempts = max(1, (int)$maxAttempts);
        $this->windowSeconds = max(60, (int)$windowSeconds);
    }

    public function isAllowed($username, $ipAddress)
    {
        $username = self::normalizeIdentity($username);
        $ipAddress = trim((string)$ipAddress);
        $since = date('Y-m-d H:i:s', time() - $this->windowSeconds);

        $identityConditions = ['or'];
        if ($username !== '') {
            $identityConditions[] = ['actor' => $username];
        }
        if ($ipAddress !== '') {
            $identityConditions[] = ['ip_address' => $ipAddress];
        }
        if (count($identityConditions) === 1) {
            return true;
        }

        $attemptCount = Bgyslogs::find()
            ->where([
                'record_type' => 'authentication',
                'result' => 'failure',
                'islem' => 'başarısız giriş',
            ])
            ->andWhere(['>=', 'date', $since])
            ->andWhere($identityConditions)
            ->count();

        return (int)$attemptCount < $this->maxAttempts;
    }

    public static function normalizeIdentity($username)
    {
        return mb_substr(mb_strtolower(trim((string)$username), 'UTF-8'), 0, 255);
    }
}
