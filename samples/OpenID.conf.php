<?php
// vim:set filetype=php:

/*
 * This is a sample configuration file.
 * @package phpPoA2
 * @subpackage AttributeFilterAuthorizationEngine
 */

/*
 * OpenID configuration file.
 */
$openid_cfg = array(

// URL to show when the user needs authentication.
// authenticate() method will redirect automatically here if needed
'LoginURL'	=> '',

// URL where to redirect to when logging out
'LogoutURL'	=> '',

// whether to trigger the authentication proccess automatically or not.
// authenticate() method won't ask the user and use the first provider with an IDstart defined.
// if LoginURL is defined, then it will have preference over automatic login
'AutoLoginAuto'	=> false,

// OpenID providers allowed
// leave empty to allow any provider
'Providers'	=> array()
);

$openid_cfg['openid1'] = array(
'Providers' => array( // OpenID Providers
	'SIR' => array('IDstart' => 'https://yo.rediris.es/soy', // initial OpenID identifier (string)
                       'IDend' => '#^https?://yo\.rediris\.es/soy/([^@]+)@((\w+\.)+[a-z]{2,4})/$#', // final identifier (regular expression)
	               'IDfields' => array('user', 'domain')), // fields to extract from the final identifier
	'Google' => array ('IDstart' => 'https://www.google.com/accounts/o8/id',
	                   'IDend' => '#^https?://www\.google\.com/accounts/o8/id\?id=(\w+)$#',
	                   'IDfields' => array('user')),
	'OpenID' => array ('IDstart' => '',
	                   'IDend' => '#^(\.+)$#',
	                   'IDfields' => array('user')),
	),
);

$openid_cfg['openid2'] = $openid_cfg['openid1'];
$openid_cfg['openid2']['AutoLogin'] = true;

$openid_cfg['openid3']= array(
'LoginURL' => 'openid-ext-form.html'
);
?>
