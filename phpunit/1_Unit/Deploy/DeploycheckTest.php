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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class DeploycheckTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function testGetTypes() {
      $types = PluginFusioninventoryDeployCheck::getTypes();
      $this->assertEquals(3, count($types));
      $this->assertEquals(6, count($types[__('Registry', 'fusioninventory')]));
      $this->assertEquals(7, count($types[__('File')]));
      $this->assertEquals(1, count($types[__('Other')]));

   }

   /**
    * @test
    */
   public function getGetLabelForAType() {
      $this->assertEquals(__("Registry key exists", 'fusioninventory'),
                          PluginFusioninventoryDeployCheck::getLabelForAType('winkeyExists'));
      $this->assertEquals(__("Free space is greater than", 'fusioninventory'),
                          PluginFusioninventoryDeployCheck::getLabelForAType('freespaceGreater'));
     $this->assertEquals('',
                         PluginFusioninventoryDeployCheck::getLabelForAType('foo'));

   }

   /**
    * @test
    */
   public function testGetUnitLabel() {
      $units = PluginFusioninventoryDeployCheck::getUnitLabel();
      $this->assertEquals(4, count($units));
      $this->assertEquals($units, [ "B"  => __("B", 'fusioninventory'),
                                    "KB" => __("KiB", 'fusioninventory'),
                                    "MB" => __("MiB", 'fusioninventory'),
                                    "GB" => __("GiB", 'fusioninventory')
                                  ]);

   }

   /**
    * @test
    */
   public function testGetAuditDescription() {
      $description
         = PluginFusioninventoryDeployCheck::getAuditDescription('winkeyEquals', 'skip');
      $this->assertEquals("Registry value equals to : continue, otherwise : skip job", $description);

      $description
         = PluginFusioninventoryDeployCheck::getAuditDescription('winkeyEquals', 'passed');
      $this->assertEquals("Registry value equals to : passed, otherwise : ", $description);

      $description
         = PluginFusioninventoryDeployCheck::getAuditDescription('winkeyEquals', 'info');
      $this->assertEquals("Registry value equals to : passed, otherwise : report info", $description);

      $description
         = PluginFusioninventoryDeployCheck::getAuditDescription('winkeyEquals', 'warning');
      $this->assertEquals("Registry value equals to : passed, otherwise : report warning", $description);
   }


   /**
    * @test
    */
   public function testGetUnitSize() {
      $this->assertEquals(PluginFusioninventoryDeployCheck::getUnitSize('B'), '1');
      $this->assertEquals(PluginFusioninventoryDeployCheck::getUnitSize('KB'), '1024');
      $this->assertEquals(PluginFusioninventoryDeployCheck::getUnitSize('MB'), '1048576');
      $this->assertEquals(PluginFusioninventoryDeployCheck::getUnitSize('GB'), '1073741824');
   }

   /**
    * @test
    */
   public function testGetRegistryTypes() {
      $this->assertEquals(10, count(PluginFusioninventoryDeployCheck::getRegistryTypes()));
   }

   /**
    * @test
    */
   public function testGetRegistryTypeLabel() {
      $this->assertEquals('', PluginFusioninventoryDeployCheck::getRegistryTypeLabel('foo'));
      $this->assertEquals('', PluginFusioninventoryDeployCheck::getRegistryTypeLabel(null));
      $this->assertEquals('REG_SZ', PluginFusioninventoryDeployCheck::getRegistryTypeLabel('REG_SZ'));
   }

   /**
    * @test
    */
   public function testGetValues() {
      $values = ['name'   => 'My check',
                 'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                 'value'  => '',
                 'return' => 'info'
                ];
      $result = PluginFusioninventoryDeployCheck::getValues('winkeyExists', $values, 'edit');
      $expected = ['name_value'  => 'My check',
                   'name_label'  => 'Audit name',
                   'name_type'   => 'input',
                   'path_label'  => "Path to the key&nbsp;<span class='red'>*</span>",
                   'path_value'  => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'value_type'  => 'input',
                   'value_label' => false,
                   'value'       => '',
                   'return'      => 'info'
                ];
      $this->assertEquals($result, $expected);


      $values = ['name'   => 'File exists',
                 'path'   => '/etc/passwd',
                 'value'  => '',
                 'return' => 'skip'
                ];
      $result = PluginFusioninventoryDeployCheck::getValues('fileExists', $values, 'edit');
      $expected = ['name_value'  => 'File exists',
                   'name_label'  => 'Audit name',
                   'name_type'   => 'input',
                   'path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'path_value'  => '/etc/passwd',
                   'value_type'  => 'input',
                   'value_label' => false,
                   'value'       => '',
                   'return'      => 'skip'
                ];
      $this->assertEquals($result, $expected);

      $values = ['name'   => 'Key equals',
                 'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                 'value'  => '2',
                 'return' => 'error'
                ];
      $result = PluginFusioninventoryDeployCheck::getValues('winkeyEquals', $values, 'edit');
      $expected = ['name_value'  => 'Key equals',
                   'name_label'  => 'Audit name',
                   'name_type'   => 'input',
                   'path_label'  => "Path to the value&nbsp;<span class='red'>*</span>",
                   'path_value'  => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'value_type'  => 'input',
                   'value_label' => 'Value',
                   'value'       => '2',
                   'return'      => 'error'
                ];
      $this->assertEquals($result, $expected);
   }
}
