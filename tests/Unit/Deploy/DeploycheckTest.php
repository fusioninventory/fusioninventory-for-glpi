<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DeploycheckTest extends TestCase {


   /**
    * @test
    */
   public function testGetTypes() {
      $check = new PluginFusioninventoryDeployCheck();
      $types = $check->getTypes();
      $this->assertEquals(4, count($types));
      $this->assertEquals(7, count($types[__('Registry', 'fusioninventory')]));
      $this->assertEquals(7, count($types[__('File')]));
      $this->assertEquals(2, count($types[__('Directory')]));
      $this->assertEquals(1, count($types[__('Other')]));

   }


   /**
    * @test
    */
   public function getGetLabelForAType() {
      $check = new PluginFusioninventoryDeployCheck();

      $this->assertEquals(__("Registry key exists", 'fusioninventory'),
                          $check->getLabelForAType('winkeyExists'));
      $this->assertEquals(__("Free space is greater than", 'fusioninventory'),
                          $check->getLabelForAType('freespaceGreater'));
      $this->assertEquals('', $check->getLabelForAType('foo'));

   }


   /**
    * @test
    */
   public function testGetUnitLabel() {
      $check = new PluginFusioninventoryDeployCheck();
      $units = $check->getUnitLabel();
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
      $check       = new PluginFusioninventoryDeployCheck();
      $description = $check->getAuditDescription('winkeyEquals', 'skip');
      $this->assertEquals("Registry value equals to : continue, otherwise : skip job", $description);

      $description = $check->getAuditDescription('winkeyEquals', 'passed');
      $this->assertEquals("Registry value equals to : passed, otherwise : ", $description);

      $description = $check->getAuditDescription('winkeyEquals', 'info');
      $this->assertEquals("Registry value equals to : passed, otherwise : report info", $description);

      $description = $check->getAuditDescription('winkeyEquals', 'warning');
      $this->assertEquals("Registry value equals to : passed, otherwise : report warning", $description);
   }


   /**
    * @test
    */
   public function testGetUnitSize() {
      $check = new PluginFusioninventoryDeployCheck();
      $this->assertEquals($check->getUnitSize('B'), '1');
      $this->assertEquals($check->getUnitSize('KB'), '1024');
      $this->assertEquals($check->getUnitSize('MB'), '1048576');
      $this->assertEquals($check->getUnitSize('GB'), '1073741824');
   }


   /**
    * @test
    */
   public function testGetRegistryTypes() {
      $check = new PluginFusioninventoryDeployCheck();
      $types = $check->getRegistryTypes();
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
   public function testGetValues() {
      $check    = new PluginFusioninventoryDeployCheck();
      $values   = ['name'   => 'My check',
                   'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'value'  => '',
                   'return' => 'info'
                  ];
      $result   = $check->getValues('winkeyExists', $values, 'edit');
      $expected = ['warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                   'name_value'      => 'My check',
                   'name_label'      => 'Audit label',
                   'name_type'       => 'input',
                   'path_label'      => "Path to the key&nbsp;<span class='red'>*</span>",
                   'path_value'      => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'path_comment'    => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'value_type'      => 'input',
                   'value_label'     => false,
                   'value'           => '',
                   'return'          => 'info'
                ];
      $this->assertEquals($result, $expected);

      $values = ['name'   => 'File exists',
                 'path'   => '/etc/passwd',
                 'value'  => '',
                 'return' => 'skip'
                ];

      $result   = $check->getValues('fileExists', $values, 'edit');
      $expected = ['warning_message' => false,
                   'name_value'      => 'File exists',
                   'name_label'      => 'Audit label',
                   'name_type'       => 'input',
                   'path_label'      => "File&nbsp;<span class='red'>*</span>",
                   'path_comment'    => '',
                   'path_value'      => '/etc/passwd',
                   'value_type'      => 'input',
                   'value_label'     => false,
                   'value'           => '',
                   'return'          => 'skip'
                ];
      $this->assertEquals($result, $expected);

      $values = ['name'   => 'Value equals',
                 'path'   => 'HKLM\Softwares\FusionInventory-Agent\debug',
                 'value'  => '2',
                 'return' => 'error'
                ];

      $result   = $check->getValues('winkeyEquals', $values, 'edit');
      $expected = ['warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                   'name_value'      => 'Value equals',
                   'name_label'      => 'Audit label',
                   'name_type'       => 'input',
                   'path_label'      => "Path to the value&nbsp;<span class='red'>*</span>",
                   'path_value'      => 'HKLM\Softwares\FusionInventory-Agent\debug',
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'value_type'      => 'input',
                   'value_label'     => 'Value',
                   'value'           => '2',
                   'return'          => 'error'
                ];
      $this->assertEquals($result, $expected);
   }


   /**
    * @test
    */
   public function testGetLabelsAndTypes() {
      $check = new PluginFusioninventoryDeployCheck();

      //----------- winkeyExists --------------------------//
      $result   = $check->getLabelsAndTypes('winkeyExists', false);
      $expected = ['path_label'      => 'Path to the key',
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended'
                 ];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('winkeyExists', true);
      $expected = ['path_label'      => "Path to the key&nbsp;<span class='red'>*</span>",
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                ];
      $this->assertEquals($result, $expected);

      //----------- winkeyMissing --------------------------//
      $result   = $check->getLabelsAndTypes('winkeyMissing', false);
      $expected = ['path_label'      => 'Path to the key',
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended'
                ];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('winkeyMissing', true);
      $expected = ['path_label'      => "Path to the key&nbsp;<span class='red'>*</span>",
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry key: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\\',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winvalueExists --------------------------//
      $result   = $check->getLabelsAndTypes('winvalueExists', false);
      $expected = ['path_label'      => 'Path to the value',
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('winvalueExists', true);
      $expected = ['path_label'      => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'     => false,
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winkeyEquals --------------------------//
      $result   = $check->getLabelsAndTypes('winkeyEquals', false);
      $expected = ['path_label'      => 'Path to the value',
                   'value_label'     => 'Value',
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('winkeyEquals', true);
      $expected = ['path_label'      => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'     => 'Value',
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher recommended',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winkeyNotEquals --------------------------//
      $result = $check->getLabelsAndTypes('winkeyNotEquals', false);
      $expected = ['path_label'   => 'Path to the value',
                   'value_label'  => 'Value',
                   'path_comment' => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.21 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      //----------- winvalueType --------------------------//
      $result   = $check->getLabelsAndTypes('winvalueType', false);
      $expected = ['path_label'      => 'Path to the value',
                   'value_label'     => 'Type of value',
                   'value_type'      => 'registry_type',
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('winvalueType', true);
      $expected = ['path_label'      => "Path to the value&nbsp;<span class='red'>*</span>",
                   'value_label'     => "Type of value&nbsp;<span class='red'>*</span>",
                   'value_type'      => 'registry_type',
                   'path_comment'    => 'Example of registry value: HKEY_LOCAL_MACHINE\SOFTWARE\Fusioninventory-Agent\server',
                   'warning_message' => 'Fusioninventory-Agent 2.3.20 or higher mandatory',
                  ];
      $this->assertEquals($result, $expected);

      //----------- fileExists --------------------------//
      $result   = $check->getLabelsAndTypes('fileExists', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileExists', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      //----------- fileMissing --------------------------//
      $result   = $check->getLabelsAndTypes('fileMissing', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileMissing', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => false];
      $this->assertEquals($result, $expected);

      //----------- fileSizeGreater --------------------------//
      $result   = $check->getLabelsAndTypes('fileSizeGreater', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileSizeGreater', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSizeLower --------------------------//
      $result   = $check->getLabelsAndTypes('fileSizeLower', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileSizeLower', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSizeEquals --------------------------//
      $result   = $check->getLabelsAndTypes('fileSizeEquals', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileSizeEquals', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      //----------- fileSHA512 --------------------------//
      $result   = $check->getLabelsAndTypes('fileSHA512', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileSHA512', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      //----------- fileSHA512mismatch --------------------------//
      $result   = $check->getLabelsAndTypes('fileSHA512mismatch', false);
      $expected = ['path_label'  => 'File',
                   'value_label' => 'Value',
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('fileSHA512mismatch', true);
      $expected = ['path_label'  => "File&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'textarea'];
      $this->assertEquals($result, $expected);

      //----------- freespaceGreater --------------------------//
      $result   = $check->getLabelsAndTypes('freespaceGreater', false);
      $expected = ['path_label'  => 'Disk or directory',
                   'value_label' => 'Value',
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);

      $result   = $check->getLabelsAndTypes('freespaceGreater', true);
      $expected = ['path_label'  => "Disk or directory&nbsp;<span class='red'>*</span>",
                   'value_label' => "Value&nbsp;<span class='red'>*</span>",
                   'value_type'  => 'input+unit'];
      $this->assertEquals($result, $expected);
   }


   /**
   * @test
   */
   public function testGetAllReturnValues() {
      $check  = new PluginFusioninventoryDeployCheck();
      $values = $check->getAllReturnValues();
      $expected = ["error"    => __('abort job', 'fusioninventory'),
                   "skip"     => __("skip job", 'fusioninventory'),
                   "startnow" => __("start job now", 'fusioninventory'),
                   "info"     => __("report info", 'fusioninventory'),
                   "warning"  => __("report warning", 'fusioninventory')
               ];
      $this->assertEquals($values, $expected);
   }


   /**
   * @test
   */
   public function testGetValueForReturn() {
      $check = new PluginFusioninventoryDeployCheck();
      $this->assertEquals('abort job', $check->getValueForReturn('error'));
      $this->assertEquals('skip job', $check->getValueForReturn('skip'));
      $this->assertEquals('start job now', $check->getValueForReturn('startnow'));
      $this->assertEquals('report info', $check->getValueForReturn('info'));
      $this->assertEquals('report warning', $check->getValueForReturn('warning'));
      $this->assertEquals('', $check->getValueForReturn('foo'));
      $this->assertEquals('', $check->getValueForReturn(null));
   }


   /**
   * @test
   */
   public function testAdd_item() {
      $check           = new PluginFusioninventoryDeployCheck();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      $input = ['name'        => 'test1',
                'entities_id' => 0];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                 => $packages_id,
                 'name'               => 'Value exists',
                 'checkstype'         => 'winvalueExists',
                 'path'               => 'HKLM\Software\FusionInventory-Agent\debug',
                 'value'              => false,
                 'return'             => 'skip'
              ];
      $check->add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":"","return":"skip"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                 => $packages_id,
                 'name'               => 'More than 500 Mb',
                 'checkstype'         => 'freespaceGreater',
                 'path'               => '/tmp',
                 'value'              => '500',
                 'unit'               => 'MB',
                 'return'             => 'info'
              ];
      $check->add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":"","return":"skip"},{"name":"More than 500 Mb","type":"freespaceGreater","path":"/tmp","value":"0.00047683715820312","return":"info"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                 => $packages_id,
                 'name'               => 'More than 5.5 Gb',
                 'checkstype'         => 'freespaceGreater',
                 'path'               => '/tmp',
                 'value'              => '5.5',
                 'unit'               => 'GB',
                 'return'             => 'info'
              ];
      $check->add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":"","return":"skip"},{"name":"More than 500 Mb","type":"freespaceGreater","path":"/tmp","value":"0.00047683715820312","return":"info"},{"name":"More than 5.5 Gb","type":"freespaceGreater","path":"/tmp","value":"5.2452087402344E-6","return":"info"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

      //Test that 5,5 is converted in 5.5 before computing the value in byte
      $params = ['id'                 => $packages_id,
                 'name'               => 'More than 5.5 Gb  #2',
                 'checkstype'         => 'freespaceGreater',
                 'path'               => '/tmp',
                 'value'              => '5,5',
                 'unit'               => 'GB',
                 'return'             => 'info'
              ];
      $check->add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":"","return":"skip"},{"name":"More than 500 Mb","type":"freespaceGreater","path":"/tmp","value":"0.00047683715820312","return":"info"},{"name":"More than 5.5 Gb","type":"freespaceGreater","path":"/tmp","value":"5.2452087402344E-6","return":"info"},{"name":"More than 5.5 Gb  #2","type":"freespaceGreater","path":"/tmp","value":"5.2452087402344E-6","return":"info"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

      //Test that a float value like 9.20 is not converted in 9.2
      $params = ['id'                 => $packages_id,
                 'name'               => 'Test with float',
                 'checkstype'         => 'winkeyEquals',
                 'path'               => 'HKEY_LOCAL_MACHINE\SOFTWARE\FusionInventory-Agent\debug',
                 'value'              => '9.20',
                 'unit'               => '',
                 'return'             => 'info'
              ];
      $check->add_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\Software\FusionInventory-Agent\debug","value":"","return":"skip"},{"name":"More than 500 Mb","type":"freespaceGreater","path":"/tmp","value":"0.00047683715820312","return":"info"},{"name":"More than 5.5 Gb","type":"freespaceGreater","path":"/tmp","value":"5.2452087402344E-6","return":"info"},{"name":"More than 5.5 Gb  #2","type":"freespaceGreater","path":"/tmp","value":"5.2452087402344E-6","return":"info"},{"name":"Test with float","type":"winkeyEquals","path":"HKEY_LOCAL_MACHINE\SOFTWARE\FusionInventory-Agent\debug","value":"9.20","return":"info"}],"associatedFiles":[],"actions":[],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

   }


   /**
   * @test
   */
   public function testSave_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueExists","path":"HKLM\\Software\\FusionInventory-Agent\\debug","value":false,"return":"skip"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $check           = new PluginFusioninventoryDeployCheck();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                 => $packages_id,
                 'index'              => 0,
                 'name'               => 'Value type is REG_SZ',
                 'checkstype'         => 'winvalueType',
                 'path'               => 'HKLM\Software\FusionInventory-Agent\debug',
                 'value'              => 'REG_SZ',
                 'return'             => 'info'
              ];
      $check->save_item($params);
      $expected = '{"jobs":{"checks":[{"name":"Value type is REG_SZ","type":"winvalueType","path":"HKLM\\Software\\FusionInventory-Agent\\debug","value":"REG_SZ","return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($check->getJson($packages_id));
      $this->assertEquals($expected, $json);

   }


   /**
   * @test
   */
   public function testRemove_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $check           = new PluginFusioninventoryDeployCheck();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      $check->remove_item(['packages_id'   => $packages_id,
                           'check_entries' => [1 => 'on']]);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = $check->getJson($packages_id);
      $this->assertEquals($expected, $json);

      $check->remove_item(['packages_id'   => $packages_id,
                           'check_entries' => [0 => 'on']]);
      $expected = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = $check->getJson($packages_id);
      $this->assertEquals($expected, $json);
   }


   /**
   * @test
   */
   public function testMove_item() {
      $json = '{"jobs":{"checks":[{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"},{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';

      $check           = new PluginFusioninventoryDeployCheck();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      $check->move_item(['id'        => $packages_id,
                         'old_index' => 0,
                         'new_index' => 1]);
      $expected = '{"jobs":{"checks":[{"name":"More than 500Mb","type":"freespaceGreater","path":"/tmp","value":500,"return":"info"},{"name":"Value exists","type":"winvalueType","path":"debug","value":"REG_SZ","return":"error"}],"associatedFiles":[],"actions":[]},"associatedFiles":[]}';
      $json     = $check->getJson($packages_id);
      $this->assertEquals($expected, $json);
   }
}
