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
      $action = new PluginFusioninventoryDeployAction();
      $this->assertEquals(5, count($action->getReturnActionNames()));

   }

   /**
    * @test
    */
   public function getGetTypes() {
      $action = new PluginFusioninventoryDeployAction();
      $this->assertEquals(5, count($action->getTypes()));
   }

   /**
    * @test
    */
   public function testGetLabelForAType() {
      $action = new PluginFusioninventoryDeployAction();
      $this->assertEquals(__('Command', 'fusioninventory'),
                          $action->getLabelForAType('cmd'));
      $this->assertEquals(__('Move', 'fusioninventory'),
                          $action->getLabelForAType('move'));
      $this->assertEquals(__('Copy', 'fusioninventory'),
                          $action->getLabelForAType('copy'));
      $this->assertEquals(__('Delete directory', 'fusioninventory'),
                          $action->getLabelForAType('delete'));
      $this->assertEquals(__('Create directory', 'fusioninventory'),
                          $action->getLabelForAType('mkdir'));
      $this->assertEquals('foo',
                          $action->getLabelForAType('foo'));
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

      $action = new PluginFusioninventoryDeployAction();
      $params = ['id'                => $packages_id,
                 'actionstype'       => 'cmd',
                 'name'              => 'Command ls',
                 'exec'              => 'ls -lah',
                 'logLineLimit'      => '100',
                 'retChecks'         => ['type' => 'okCode', 'values' => [127]],
                ];
      $action->add_item($params);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}}],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'actionstype'       => 'move',
                 'name'              => 'Move to /tmp',
                 'from'              => '*',
                 'to'                => '/tmp/'
                ];
      $action->add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}}],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'actionstype'       => 'copy',
                 'name'              => 'Copy to /tmp',
                 'from'              => '*',
                 'to'                => '/tmp/'
                ];
      $action->add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}}],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
      $this->assertEquals($expected, $json);

      $params = ['id'                => $packages_id,
                 'actionstype'       => 'mkdir',
                 'name'              => 'Create directory /tmp/foo',
                 'to'                => '/tmp/foo'
                ];
      $action->add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}}],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
      $this->assertEquals($expected, $json);
      $params = ['id'                => $packages_id,
                 'actionstype'       => 'delete',
                 'name'              => 'Delete directory /tmp/foo',
                 'to'                => '/tmp/foo'
                ];
      $action->add_item($params);

      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}],"userinteractions":[]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
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

      $action = new PluginFusioninventoryDeployAction();
      $params = ['id'                => $packages_id,
                 'index'             => 0,
                 'actionstype'       => 'cmd',
                 'name'              => 'Command ls -la \'s',
                 'exec'              => 'ls -la',
                 'logLineLimit'      => '100',
                 'retChecks'         => ['type' => 'okCode', 'values' => [127]],
                ];
      $action->save_item($params);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -la","name":"Command ls -la \'s","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = Toolbox::stripslashes_deep($action->getJson($packages_id));
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

      $action = new PluginFusioninventoryDeployAction();
      $action->remove_item(['packages_id'    => $packages_id,
                            'action_entries' => [0 => 'on']]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = $action->getJson($packages_id);
      $this->assertEquals($expected, $json);

      $action->remove_item(['packages_id'    => $packages_id,
                            'action_entries' => [0 => 'on', 1 => 'on']]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = $action->getJson($packages_id);
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
      $action      = new PluginFusioninventoryDeployAction();

      $action->move_item(['id'        => $packages_id,
                          'old_index' => 0,
                          'new_index' => 1]);
      $expected = '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"move":{"from":"*","to":"/tmp/","name":"Move to /tmp"}},{"cmd":{"exec":"ls -lah","name":"Command ls","logLineLimit":"100"}},{"copy":{"from":"*","to":"/tmp/","name":"Copy to /tmp"}},{"mkdir":{"to":"/tmp/foo","name":"Create directory /tmp/foo"}},{"delete":{"to":"/tmp/foo","name":"Delete directory /tmp/foo"}}]},"associatedFiles":[]}';
      $json     = $action->getJson($packages_id);
      $this->assertEquals($expected, $json);
   }
}
