<?php

// vim:set filetype=php:

// CONSTANTES PREDEFINIDAS
if (!defined('WWWROOT')) {
    define('WWWROOT', '/home/spool/SIR/conf/papi/');
}
if (!defined('AUTHZ_CONF_DIR')) {
    define('AUTHZ_CONF_DIR', WWWROOT.'authz/');
}
if (!defined('AUTHN_CONF_DIR')) {
    define('AUTHN_CONF_DIR', WWWROOT.'authn/');
}
if (!defined('SIR_REDIRIS_LOG_DIR')) {
    define('SIR_REDIRIS_LOG_DIR', '/home/spool/SIR/sir.rediris.es/log/saml_gateway/');
}
if (!defined('YO_REDIRIS_LOG_DIR')) {
    define('YO_REDIRIS_LOG_DIR', '/home/spool/SIR/yo.rediris.es/log/');
}
if (!defined('IP_CONF_FILE')) {
    define('IP_CONF_FILE', AUTHZ_CONF_DIR.'ip.conf');
}
if (!defined('ATTR_CONF_FILE')) {
    define('ATTR_CONF_FILE', AUTHZ_CONF_DIR.'attributes.conf');
}
if (!defined('URLS_CONF_FILE')) {
    define('URLS_CONF_FILE', AUTHZ_CONF_DIR.'urls.conf');
}

$poa_cfg = [
    'LogFile' => SIR_REDIRIS_LOG_DIR.'phpPoA.log',
    'Debug' => true,
    //'LogLevel' => E_USER_WARNING,
    'LogLevel' => E_ALL,
    'Language' => 'es_ES',
    'NoAuthErrorURL' => 'https://www.rediris.es/error.php?status=403',
    'SystemErrorURL' => 'https://www.rediris.es/error.php?status=503',
    'InviteErrorURL' => 'https://www.rediris.es/null/error.php?status=503',
    'AuthnEngine' => 'PAPIAuthnEngine',
    'AuthnEngineConfFile' => dirname(__FILE__).'/authn/papi.conf',
    //        'AuthzEngines' => array('SourceIPAddrAuthzEngine'),
    //        'AuthzEnginesConfFiles' => array('SourceIPAddrAuthzEngine' => IP_CONF_FILE),
];

$poa_cfg['sir1_sirgpoa'] = [
    'LogLevel' => E_ALL,
    'Debug' => true,
    'LogFile' => SIR_REDIRIS_LOG_DIR.'sir1_sirgpoa.log',
];

$poa_cfg['sir1_sirtestgpoa'] = [
    'LogLevel' => E_ALL,
    'Debug' => true,
    'LogFile' => SIR_REDIRIS_LOG_DIR.'sir1_sirtestgpoa.log',
];

// GATEWAY OPENID
$poa_cfg['papoid'] = [
    'LogLevel' => E_ALL,
    'Debug' => true,
    'LogFile' => YO_REDIRIS_LOG_DIR.'papoid.log',
    'AuthzEngines' => ['QueryFilterAuthzEngine'],
    'AuthzEnginesConfFiles' => ['QueryFilterAuthzEngine' => URLS_CONF_FILE],
];

// GATEWAY MSDNAA
$poa_cfg['msdnaav2'] = [
    'Debug' => true,
    'LogFile' => '/home/spool/SIR/sir.rediris.es/log/msdnaa.log',
    'AuthzEngines' => ['AttributeFilterAuthzEngine'],
    'AuthzEnginesConfFiles' => ['AttributeFilterAuthzEngine' => ATTR_CONF_FILE],
    'NoAuthErrorURL' => 'https://sir.rediris.es/msdnaa/error.php',
    'SystemErrorURL' => 'https://sir.rediris.es/msdnaa/error.php',
    'InviteErrorURL' => 'https://sir.rediris.es/msdnaa/error.php',
];
