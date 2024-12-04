<?php
/**
 * This is a sample file to demonstrate the functionality of the phpPoA2 package.
 * @author Miguel Macías <miguel.macias@upv.es>
 * @filesource
 * @package phpPoA2
 */

include('../PoA.php');

$poa = new PoA('openid1');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>OpenID restricted providers</title>
 </head>

 <body>
  <h1>OpenID restricted providers</h1>
<?php
$auth = $poa->authenticate();
if ($auth) {
?>
  <p><strong>authenticate()</strong>: <div style="background: #ccffcc; padding: 5px"><tt>AUTHN_SUCCESS</tt></div></p>
  <p><strong>getAttributes()</strong>:</p>
  <div style="background: #cccccc; padding: 5px"><pre><?=print_r($poa->getAttributes());?></pre></div>
<?php
} else {
?>
  <p><strong>authenticate()</strong>: <div style="background: #ffcccc; padding: 5px"><tt>AUTHN_FAILED</tt></div></p>
  <form method="post" action="">
   <p>Please select your identity provider:</p>
   <p>
    <button type="submit" name="openid_identifier" value="https://yo.rediris.es/soy">
     <img src="https://www.rediris.es/sir/imgs/sir-a3b1.png" alt="SIR" />
    </button>
    <button type="submit" name="openid_identifier" value="https://www.google.com/accounts/o8/id">
     <img src="https://www.google.com/intl/es_ES/images/logos/accounts_logo.gif" alt="Google" />
    </button>
   </p>
  </form>
<?php
}
?>
 </body>
</html>
