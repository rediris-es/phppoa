<?php
/**
 * @copyright Copyright 2005-2012 RedIRIS, http://www.rediris.es/
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
 * @version 2.5
 * @author Jaime Perez <jaime.perez@rediris.es>
 * @filesource
 */
namespace RedIRIS\PoA\Conf\db;
// Invalidate deleted keys for half a minute to avoid replay attacks
define("KEY_INVALIDATION_TIME", 30);

// Key index array name
define("KEY_INDEX", "INDEX");

/**
 * Memcached NOSQL database backend.
 * @package phpPoA2
 * @subpackage GenericDatabaseHandlers
 */
class MemcachedDB extends GenericDB {

    protected $mandatory_options = array("DBServers",
                                         "DBPrefix");

    protected $mc;
    protected $expire_time = 0;

    protected function configure() {
        if (!extension_loaded("memcached")) {
            trigger_error(PoAUtils::msg('extension-required', array("memcached")), E_USER_ERROR);
        }
        parent::configure();
    }

    public function open() {
        $this->mc = new Memcached();
        $this->mc->setOption(Memcached::OPT_PREFIX_KEY, $this->cfg->getDBPrefix());
        $this->mc->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_JSON);

        $sl = $this->mc->getServerList();
        if (empty($sl)) {
            $this->mc->addServers($this->cfg->getDBServers());
        }
        return true;
    }

    public function check($key) {
        $this->mc->get($key);
        return $this->mc->getResultCode() === Memcached::RES_SUCCESS;
    }

    public function replace($key, $value) {
        if ($this->mc->set($key, $value, $this->expire_time)) {
            $keys = $this->mc->get(KEY_INDEX);
            $keys[$key] = true;
            $this->mc->set(KEY_INDEX, $keys);
            return true;
        }
        return false;
    }

    public function fetch($key) {
        return $this->mc->get($key);
    }

    public function fetch_all() {
        $rslt = array();
        $keys = $this->mc->get(KEY_INDEX);
        if (!empty($keys)) {
            foreach ($keys as $key => $value) {
                $rslt[$key] = $this->mc->get($key);

                // if get failed, remove it from list
                if ($this->mc->getResultCode() !== Memcached::RES_SUCCESS) {
                    unset($rslt[$key]);
                    $keys = $this->mc->get(KEY_INDEX);
                    unset($keys[$key]);
                    $this->mc->set(KEY_INDEX, $keys);
                }
            }
        }
        return $rslt;;
    }

    public function delete($key) {
        if ($this->mc->delete($key, KEY_INVALIDATION_TIME)) {
            $keys = $this->mc->get(KEY_INDEX);
            unset($keys[$key]);
            $this->mc->set(KEY_INDEX, $keys);
            return true;
        }
        return false;
    }

    public function close() {
        unset($this->mc);
        return true;
    }
}

?>
