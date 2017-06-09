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
      $this->assertEquals($units, [ "B"  => __('o'),
                                    "KB" => __('Kio'),
                                    "MB" => __('Mio'),
                                    "GB" => __('Gio')
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
      $types = PluginFusioninventoryDeployCheck::getRegistryTypes();
      $this->assertEquals(8, count($types));
      $expected = ['REG_SZ'          => 'REG_SZ',
              'REG_DWORD'            => 'REG_DWORD',
              'REG_BINARY'           => 'REG_BINARY',
              'REG_EXPAND_SZ'        => 'REG_EXPAND_SZ',
              'REG_MULTI_SZ'         => 'REG_MULTI_SZ',
              'REG_LINK'             => 'REG_LINK',
              'REG_DWORD_BIG_ENDIAN' => 'REG_DWORD_BIG_ENDIAN',
              'REG_NONE'             => 'REG_NONE'
           ];
      $this->assertEquals($expected, $types);
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
      $values   = ['name'   => 'My check',
                   'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'value'  => '',
                   'return' => 'info'
                  ];
      $result   = PluginFusioninventoryDeployCheck::getValues('winkeyExists', $values, 'edit');
      $expected = ['warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                   'name_value'  => 'My check',
                   'name_label'  => 'Audit label',
                   'name_type'   => 'input',
                   'path_label'  => "Path to the key&nbsp;<span class='red'>*</span>",
                   'path_value'  => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'path_comment'=> 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
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
      $expected = ['warning_message' => false,
                   'name_value'  => 'File exists',
                   'name_label'  => 'Audit label',
                   'name_type'   => 'input',
                   'path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'path_comment'=> '',
                   'path_value'  => '/etc/passwd',
                   'value_type'  => 'input',
                   'value_label' => false,
                   'value'       => '',
                   'return'      => 'skip'
                ];
      $this->assertEquals($result, $expected);

      $values = ['name'   => 'Value equals',
                 'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                 'value'  => '2',
                 'return' => 'error'
                ];
      $result = PluginFusioninventoryDeployCheck::getValues('winkeyEquals', $values, 'edit');
      $expected = ['warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                   'name_value'  => 'Value equals',
                   'name_label'  => 'Audit label',
                   'name_type'   => 'input',
                   'path_label'  => "Path to the value&nbsp;<span class='red'>*</span>",
                   'path_value'  => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'path_comment'=> 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'value_type'  => 'input',
                   'value_label' => 'Value',
                   'value'       => '2',
                   'return'      => 'error'
                ];
      $this->assertEquals($result, $expected);
   }

   /**
    * @test
    */
   public function testGetLabelsAndTypes() {

      //----------- winkeyExists --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyExists', false);
      $expected = ['path_label'   => 'Path to the key',
                   'value_label'  => false,
                   'path_comment' => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended'
                 ];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyExists', true);
      $expected = ['path_label'   => "Path to the key&nbsp;<span class='red'>*</span>",
                   'value_label'  => false,
                   'path_comment' => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                ];
      $this->assertEquals($result, $expected);

      //----------- winkeyMissing --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyMissing', false);
      $expected = ['path_label'   => 'Path to the key',
                   'value_label'  => false,
                   'path_comment' => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended'
                ];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyMissing', true);
      $expected = ['path_label'   => "Path to the key&nbsp;<span class='red'>*</span>",
                   'value_label'  => false,
                   'path_comment' => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winvalueExists --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winvalueExists', false);
      $expected = ['path_label'   => 'Path to the value',
                   'value_label'  => false,
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winvalueExists', true);
      $expected = ['path_label'   => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'  => false,
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winkeyEquals --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyEquals', false);
      $expected = ['path_label'   => 'Path to the value',
                   'value_label'  => 'Value',
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winkeyEquals', true);
      $expected = ['path_label'   => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'  => 'Value',
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winvalueType --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winvalueType', false);
      $expected = ['path_label'   => 'Path to the value',
                   'value_label'  => 'Type of value',
                   'value_type'   => 'registry_type',
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('winvalueType', true);
      $expected = ['path_label'   => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'  => "Type of value&nbsp;<span class='red'>*</span>",
                   'value_type'   => 'registry_type',
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      //----------- fileExists --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileExists', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileExists', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      //----------- fileMissing --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileMissing', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileMissing', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      //----------- fileSizeGreater --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeGreater', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeGreater', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSizeLower --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeLower', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeLower', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSizeEquals --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeEquals', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSizeEquals', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSHA512 --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSHA512', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSHA512', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      //----------- fileSHA512mismatch --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSHA512mismatch', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('fileSHA512mismatch', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      //----------- freespaceGreater --------------------------//
      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('freespaceGreater', false);
      $expected = ['path_label'  => 'Disk or directory',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result = PluginFusioninventoryDeployCheck::getLabelsAndTypes('freespaceGreater', true);
      $expected = ['path_label'  => "Disk or directory&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);
   }

   /**
   * @test
   */
   public function testGetAllReturnValues() {
      $values = PluginFusioninventoryDeployCheck::getAllReturnValues();
      $expected = ["error"   => __('abort job', 'fusioninventory'),
                  "skip"     => __("skip job", 'fusioninventory'),
                  "info"     => __("report info", 'fusioninventory'),
                  "warning"  => __("report warning", 'fusioninventory')
               ];
      $this->assertEquals($values, $expected);
   }

   /**
   * @test
   */
   public function testGetValueForReturn() {
      $this->assertEquals('abort job', PluginFusioninventoryDeployCheck::getValueForReturn('error'));
      $this->assertEquals('skip job', PluginFusioninventoryDeployCheck::getValueForReturn('skip'));
      $this->assertEquals('report info', PluginFusioninventoryDeployCheck::getValueForReturn('info'));
      $this->assertEquals('report warning', PluginFusioninventoryDeployCheck::getValueForReturn('warning'));
      $this->assertEquals('', PluginFusioninventoryDeployCheck::getValueForReturn('foo'));
      $this->assertEquals('', PluginFusioninventoryDeployCheck::getValueForReturn(null));
   }

   /**
   * @test
   */
   public function testAdd_item() {
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                 => $packages_id,
                 'name'               => 'Value exists',
                 'deploy_checktype'   => 'winvalueExists',
                 'path'               => 'HKLM\Software\FusionInventory-Agent\debug',
                 'value'              => false,
                 'return'             => 'skip'
              ];
      PluginFusioninventoryDeployCheck::add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\\Software\\FusionInventory-Agent\\debug","value":false,"return":"skip"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                 => $packages_id,
                 'name'               => 'More than 500 Mb',
                 'deploy_checktype'   => 'freespaceGreater',
                 'path'               => '/tmp',
                 'value'              => '500',
                 'unit'               => 'MB',
                 'return'             => 'info'
              ];
      PluginFusioninventoryDeployCheck::add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":false,"return":"skip"},{"name":"More than 500 Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

   }

   /**
   * @test
   */
   public function testSave_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\\Software\\FusionInventory-Agent\\debug","value":false,"return":"skip"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                 => $packages_id,
                 'index'              => 0,
                 'name'               => 'Value type is REG_SZ',
                 'deploy_checktype'   => 'winvalueType',
                 'path'               => 'HKLM\Software\FusionInventory-Agent\debug',
                 'value'              => 'REG_SZ',
                 'return'             => 'info'
              ];
      PluginFusioninventoryDeployCheck::save_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value type is REG_SZ","type":"winvalueType","path":"HKLM\\Software\\FusionInventory-Agent\\debug","value":"REG_SZ","return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

   }

   /**
   * @test
   */
   public function testRemove_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      PluginFusioninventoryDeployCheck::remove_item(['packages_id'   => $packages_id,
                                                     'check_entries' => [1 => 'on']]);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);

      PluginFusioninventoryDeployCheck::remove_item(['packages_id'   => $packages_id,
                                                     'check_entries' => [0 => 'on']]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);
   }

   /**
   * @test
   */
   public function testMove_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      PluginFusioninventoryDeployCheck::move_item(['id'        => $packages_id,
                                                   'old_index' => 0,
                                                   'new_index' => 1]);
      $expected = '{"jobs":{"checks":[{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"},{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);
   }
}
