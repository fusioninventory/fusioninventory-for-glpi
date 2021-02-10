<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2016-2021 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2016-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2016

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DeleteLinkedObjectsTest extends TestCase {

   /**
    * @test
    */
   public function IpRangeDeleteConfigSecurity() {

      $iprange = new PluginFusioninventoryIPRange();
      $iprange_ConfigSecurity = new PluginFusioninventoryIPRange_ConfigSecurity();

      // Delete all IPRanges
      $items = $iprange->find();
      foreach ($items as $item) {
         $iprange->delete(['id' => $item['id']], true);
      }

      $input = [
          'name'        => 'Office',
          'ip_start'    => '192.168.0.1',
          'ip_end'      => '192.168.0.254',
          'entities_id' => 0
      ];
      $ipranges_id = $iprange->add($input);

      $list_iprange = $iprange->find();
      $this->assertEquals(1, count($list_iprange), "IP Range not right added");

      $input = [
          'plugin_fusioninventory_ipranges_id' => $ipranges_id,
          'plugin_fusioninventory_configsecurities_id' => 1,
          'rank' => 1
      ];
      $iprange_ConfigSecurity->add($input);

      $list_security = $iprange_ConfigSecurity->find();
      $this->assertEquals(1, count($list_security), "SNMP community not added to iprange");

      $iprange->delete(['id' => $ipranges_id]);

      $list_iprange = $iprange->find();
      $this->assertEquals(0, count($list_iprange), "IP Range not right deleted");

      $list_security = $iprange_ConfigSecurity->find();
      $this->assertEquals(0, count($list_security), "SNMP community not deleted with iprange");
   }
}
