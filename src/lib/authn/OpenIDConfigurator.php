<?php
/**
 * @copyright Copyright 2005-2010 RedIRIS, http://www.rediris.es/
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
 * Configurator class for the OpenID Authentication Engine.
 * @package phpPoA2
 * @subpackage OpenIDAuthenticationEngine
 */
class OpenIDConfigurator extends GenericConfigurator {

    protected $mandatory_options = array('Providers');

    /**
     * 
     * @return string 
     */
    public function getLoginURL() {
        return (array_key_exists('LoginURL', $this->cfg)) ? $this->cfg['LoginURL'] : '';
    }

    /**
     * 
     * @return string
     */
    public function getLogoutURL() {
        return (array_key_exists('LogoutURL', $this->cfg)) ? $this->cfg['LogoutURL'] : '';
    }
    /**
     * 
     * @return boolean
     */
    public function isAutoLogin() {
        return (array_key_exists('AutoLogin', $this->cfg)) ? $this->cfg['AutoLogin'] === true : false;
    }

    /**
     * 
     * @return array 
     */
    public function getProviders() {
        return $this->cfg['Providers'];
    }
}

?>
