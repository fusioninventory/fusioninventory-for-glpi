<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    Johan Cwiklinski
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2016

   ------------------------------------------------------------------------
 */

class CollectsTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollect = new PluginFusioninventoryCollect();

      $input = array(
         'name'         => 'Registry collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
     );
      $collects_id = $pfCollect->add($input);


      $input = array(
         'name'                                 => 'Registry collection',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'hive'                                 => 'HKEY_LOCAL_MACHINE',
         'path'                                 => '/',
         'key'                                  => 'daKey'
      );

      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $pfCollect_Registry->add($input);

      $input = array(
          'name'                                => 'WMI',
          'plugin_fusioninventory_collects_id'  => $collects_id,
          'moniker'                             => 'DaWMI'
      );

      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $pfCollect_Wmi->add($input);

      $input = array(
         'name'                                 => 'PHP files',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'dir'                                  => '/var/www',
         'is_recursive'                         => 1,
         'filter_regex'                         => '*\.php',
         'filter_is_file'                       => 1,
         'filter_is_dir'                        => 0
      );

      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $pfCollect_File->add($input);
   }

   /**
    * @test
    */
   public function getSearchOptionsToAdd()
   {
      $pfCollect = new PluginFusioninventoryCollect();
      $sopts = $pfCollect->getSearchOptionsToAdd();

      $this->assertEquals(4, count($sopts));

      $expected = array(
         'table'            => 'glpi_plugin_fusioninventory_collects_registries_contents',
         'field'            => 'value',
         'linkfield'        => '',
         'name'             => __('Registry', 'fusioninventory')." - Registry collection",
         'joinparams'       => array('jointype' => 'child'),
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => array(
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_registries_id` = 1",
            'jointype'  => 'child'
         )
      );
      $this->assertEquals($expected, $sopts[5200]);

      $expected = array(
         'table'            => 'glpi_plugin_fusioninventory_collects_wmis_contents',
         'field'            => 'value',
         'linkfield'        => '',
         'name'             => __('WMI', 'fusioninventory')." - WMI",
         'joinparams'       => array('jointype' => 'child'),
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => array(
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_wmis_id` = 1",
            'jointype'  => 'child'
         )
      );
      $this->assertEquals($expected, $sopts[5201]);

      $expected = array(
         'table'            => 'glpi_plugin_fusioninventory_collects_files_contents',
         'field'            => 'pathfile',
         'linkfield'        => '',
         'name'             => __('Find file', 'fusioninventory')." - PHP files".
            " - ".__('pathfile', 'fusioninventory'),
         'joinparams'       => array('jointype' => 'child'),
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => array(
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = 1",
            'jointype'  => 'child'
         )
      );
      $this->assertEquals($expected, $sopts[5202]);

      $expected = array(
         'table'            => 'glpi_plugin_fusioninventory_collects_files_contents',
         'field'            => 'size',
         'linkfield'        => '',
         'name'             => __('Find file', 'fusioninventory'). " - PHP files".
                                    " - ".__('Size', 'fusioninventory'),
         'joinparams'       => array('jointype' => 'child'),
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => array(
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = 1",
            'jointype'  => 'child'
         )
      );
      $this->assertEquals($expected, $sopts[5203]);
   }

}
