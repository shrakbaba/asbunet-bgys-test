<?php

$params = [
    'adminEmail' => 'sistemdestek@asbu.edu.tr',
    'domain' =>'http://kast.asbu.edu.tr',
    'giristipi' =>1, //1 ad  0 local
    'mailadresi' =>'asbunet.webservis@asbu.edu.tr', //Yii::$app->params['mailadresi']
    'cookieValidationKey' => getenv('BGYS_COOKIE_VALIDATION_KEY') ?: '',
    'nessusBaseUrl' => getenv('BGYS_NESSUS_BASE_URL') ?: '',
    'nessusAccessKey' => getenv('BGYS_NESSUS_ACCESS_KEY') ?: '',
    'nessusSecretKey' => getenv('BGYS_NESSUS_SECRET_KEY') ?: '',
    'nessusUsername' => getenv('BGYS_NESSUS_USERNAME') ?: '',
    'nessusPassword' => getenv('BGYS_NESSUS_PASSWORD') ?: '',
    'nessusVerifySsl' => true,
    'snmpHost' => getenv('BGYS_SNMP_HOST') ?: '',
    'snmpReadCommunity' => getenv('BGYS_SNMP_READ_COMMUNITY') ?: '',
    'snmpWriteCommunity' => getenv('BGYS_SNMP_WRITE_COMMUNITY') ?: '',
    'sessionTimeout' => (int)(getenv('BGYS_SESSION_TIMEOUT') ?: 3600),
    'secureCookies' => getenv('BGYS_SECURE_COOKIES') === '0' ? false : true,
    'ldapPort' => (int)(getenv('BGYS_LDAP_PORT') ?: 389),
    'ldapUseSsl' => getenv('BGYS_LDAP_USE_SSL') === '1' ? true : false,
    'ldapUseTls' => getenv('BGYS_LDAP_USE_TLS') === '1' ? true : false,
];

$localParamsFile = __DIR__ . '/params_local.php';
$localParams = file_exists($localParamsFile) ? require $localParamsFile : [];

return array_merge($params, $localParams);
