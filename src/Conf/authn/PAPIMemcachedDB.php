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
namespace RedIRIS\PoA\Conf\authn;

use RedIRIS\PoA\Conf\db\MemcachedDB;

/**
 * PAPI binding to a Memcached DB backend.
 * @package phpPoA2
 * @subpackage PAPIAuthenticationEngine
 */
class PAPIMemcachedDB extends MemcachedDB implements PAPIDB {

    protected $context;

    protected function configure() {
        $this->expire_time = $this->cfg->getDBKeyExpirationTimeout();
        parent::configure();
    }

    public function replaceContents($key, $get, $post, $request, $query, $method, $input, $hli) {
        $this->context['timestamp'] = time();
        $this->context['GET'] = $get;
        $this->context['POST'] = $post;
        $this->context['REQUEST'] = $request;
        $this->context['QUERY_STRING'] = $query;
        $this->context['REQUEST_METHOD'] = $method;
        $this->context['PHP_INPUT'] = $input;
        $this->context['HLI'] = $hli;

        $this->mc->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP);
        return parent::replace($key, $this->context);
    }

    public function purge($gap) {
        return true;
    }

}

?>
