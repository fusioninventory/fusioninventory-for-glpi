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

class DeployactionTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function testGetReturnActionNames() {
      $types = PluginFusioninventoryDeployAction::getReturnActionNames();
      $this->assertEquals(5, count($types));

   }

   /**
    * @test
    */
   public function getGetTypes() {
      $types = PluginFusioninventoryDeployAction::getTypes();
      $this->assertEquals(5, count($types));
   }

   /**
    * @test
    */
   public function testGetTypeDescription() {
      $this->assertEquals(__('Command', 'fusioninventory'),
                          PluginFusioninventoryDeployAction::getTypeDescription('cmd'));
      $this->assertEquals(__('Move', 'fusioninventory'),
                          PluginFusioninventoryDeployAction::getTypeDescription('move'));
      $this->assertEquals(__('Copy', 'fusioninventory'),
                          PluginFusioninventoryDeployAction::getTypeDescription('copy'));
      $this->assertEquals(__('Delete directory', 'fusioninventory'),
                          PluginFusioninventoryDeployAction::getTypeDescription('delete'));
      $this->assertEquals(__('Create directory', 'fusioninventory'),
                          PluginFusioninventoryDeployAction::getTypeDescription('mkdir'));
      $this->assertEquals('foo',
                          PluginFusioninventoryDeployAction::getTypeDescription('foo'));
   }

   /**
   * @test
   */
   public function testAdd_item() {
      $_SESSION['glpiactiveentities_string'] = 0;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                => $packages_id,
                 'deploy_actiontype' => 'cmd',
                 'name'              => 'Command ls',
                 'exec'              => 'ls -lah',
                 'logLineLimit'      => '100',
                 'retChecks'         => ['type' => 'okCode', 'values' => [127]],
                ];
      PluginFusioninventoryDeployAction::add_item($params);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'deploy_actiontype' => 'move',
                 'name'              => 'Move to /tmp',
                 'from'              => '*',
                 'to'                => '/tmp/'
                ];
      PluginFusioninventoryDeployAction::add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'deploy_actiontype' => 'copy',
                 'name'              => 'Copy to /tmp',
                 'from'              => '*',
                 'to'                => '/tmp/'
                ];
      PluginFusioninventoryDeployAction::add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'deploy_actiontype' => 'mkdir',
                 'name'              => 'Create directory /tmp/foo',
                 'to'                => '/tmp/foo'
                ];
      PluginFusioninventoryDeployAction::add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);
      $params = ['id'                => $packages_id,
                 'deploy_actiontype' => 'delete',
                 'name'              => 'Delete directory /tmp/foo',
                 'to'                => '/tmp/foo'
                ];
      PluginFusioninventoryDeployAction::add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

   }

   /**
   * @test
   */
   public function testSave_item() {
      $json = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json];
      $packages_id = $pfDeployPackage->add($input);

      $params = ['id'                => $packages_id,
                 'index'             => 0,
                 'deploy_actiontype' => 'cmd',
                 'name'              => 'Command ls -la \'s',
                 'exec'              => 'ls -la',
                 'logLineLimit'      => '100',
                 'retChecks'         => ['type' => 'okCode', 'values' => [127]],
                ];
      PluginFusioninventoryDeployAction::save_item($params);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -la","name":"Command ls -la \'s","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep(PluginFusioninventoryDeployPackage::getJson($packages_id));
      $this->assertEquals($expected, $json);

   }

   /**
   * @test
   */
   public function testRemove_item() {
      $json = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      PluginFusioninventoryDeployAction::remove_item(['packages_id'   => $packages_id,
                                                     'action_entries' => [0 => 'on']]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);

      PluginFusioninventoryDeployAction::remove_item(['packages_id'   => $packages_id,
                                                     'action_entries' => [0 => 'on', 1 => 'on']]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);

   }

   /**
   * @test
   */
   public function testMove_item() {
      $json = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $input = ['name'        => 'test1',
                'entities_id' => 0,
                'json'        => $json
               ];
      $packages_id = $pfDeployPackage->add($input);

      PluginFusioninventoryDeployAction::move_item(['id'        => $packages_id,
                                                   'old_index' => 0,
                                                   'new_index' => 1]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = PluginFusioninventoryDeployPackage::getJson($packages_id);
      $this->assertEquals($expected, $json);
   }
}
