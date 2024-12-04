<?php
/**
 *
 * This file is part of phpPoA2.
 *
 * phpPoA2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * phpPoA2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with phpPoA2. If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @version 2.0
 * @author Miguel Macías <miguel.macias@upv.es>
 * @filesource
 */

/**
 * Authentication engine for the OpenID protocol.
 * PLEASE NOTE THAT THIS ENGINE WORKS ONLY FOR WEB-BASED APPLICATIONS.
 * @package phpPoA2
 * @subpackage OpenIDAuthnEngine
 */
class OpenIDAuthnEngine extends AuthenticationEngine {

    protected $lOpenID;
    protected $status;
    protected $attributes;

    // attributes via OpenID (AX -> SREG)
    protected $reqAttributes = array (
        'namePerson/friendly'     => 'nickname',
        'contact/email'           => 'email'
    );

    public function __construct($file, $site) {
        parent::__construct ($file, $site);

        $this->status = AUTHN_FAILED;
        $this->attributes = array();

        if (!class_exists("LightOpenID")) {
            trigger_error(PoAUtils::msg('library-required', array("LightOpenID")), E_USER_ERROR);
        }

        $this->lOpenID = new LightOpenID();       
    }

    public function configure($file,$site) {
        parent::configure ($file, $site);
    }

    public function authenticate() {
        $providers = $this->cfg->getProviders();

        if (!$this->lOpenID->mode) { // start authentication
            if (isset($_POST['openid_identifier'])) {
                // we already have an OpenID, autodetect where to go
                $this->lOpenID->identity = $_POST['openid_identifier'];
                $this->lOpenID->required = array_keys($this->reqAttributes);
                header('Location: ' . $this->lOpenID->authUrl());
                exit();
            } else { // no OpenID available
                $loginURL= $this->cfg->getLoginURL();
                if (!empty($loginURL) && $this->lOpenID->returnUrl != $loginURL) {
                    // show user custom login form
                    header('Location: ' . $loginURL);
                    exit();
                } else if ($this->cfg->isAutoLogin()) {
                    // go to the first provider available
                    foreach ($providers as $provider) {
                        if (!empty($provider['IDstart'])) {
                            $this->lOpenID->identity = $provider['IDstart'];
                            $this->lOpenID->required = array_keys($this->reqAttributes);
                            header('Location: ' . $this->lOpenID->authUrl());
                            exit();
                            break;
                        }
                    }
                }
            }
        } else if ($this->lOpenID->mode == 'cancel') { // user cancels
            $this->status = AUTHN_FAILED;
        } else { // returning back after authentication
            if ($this->lOpenID->validate()) {
                $this->status = AUTHN_SUCCESS;
                $ax_attributes = $this->lOpenID->getAttributes();
                if (!empty($ax_attributes))
                    foreach ($ax_attributes as $ax_attribute => $ax_value)
                        $ax_attributes[$this->reqAttributes[$ax_attribute]]= $ax_value;
                $ax_attributes['identity']= $this->lOpenID->identity;
                foreach ($providers as $provider) {
                    if (preg_match($provider['IDend'], $this->lOpenID->identity, $listAttr)) {
                        foreach ($provider['IDfields'] as $numAttr => $nameAttr)
                            if (isset($listAttr[$numAttr + 1]))
                                $ax_attributes[$nameAttr]= $listAttr[$numAttr + 1];
                        break;
                    }
                }
                $this->attributes = $ax_attributes;
                $this->status = AUTHN_SUCCESS;
            } else {
                $this->status = AUTHN_FAILED;
            }
        }
        return $this->status;
    }

    public function isAuthenticated() {  
        return $this->status;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function getAttribute($name, $namespace = null) {
        $attr = null;
        if (array_key_exists($name, $this->attributes)) {
            $attr = $this->attributes[$name];
        }
        return $attr;
    }

    public function logout($slo = false) {
        // first check if we really need to logout!
        if (!$this->isAuthenticated()) {
            trigger_error(PoAUtils::msg('already-logged-out', array()), E_USER_NOTICE);
            return true;
        }
        
        // there's no logout for OpenID, so we just mark the user as logged out
        $this->status = AUTHN_FAILED;
        trigger_error(PoAUtils::msg('local-logout-success', array()), E_USER_NOTICE);

        // check if we have a logout URL where to redirect
        $urlLogout = $this->cfg->getLogoutURL();
        if ($urlLogout) {
            header('Location: '.$urlLogout);
            exit();
        }
        return true;
    }
}
?>
