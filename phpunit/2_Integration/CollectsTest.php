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

      $input = [
         'name'         => 'Registry collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);

      $input = [
         'name'                                 => 'Registry collection',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'hive'                                 => 'HKEY_LOCAL_MACHINE',
         'path'                                 => '/',
         'key'                                  => 'daKey'
      ];

      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $pfCollect_Registry->add($input);

      $input = [
          'name'                                => 'WMI',
          'plugin_fusioninventory_collects_id'  => $collects_id,
          'moniker'                             => 'DaWMI'
      ];

      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $pfCollect_Wmi->add($input);

      $input = [
         'name'                                 => 'PHP files',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'dir'                                  => '/var/www',
         'is_recursive'                         => 1,
         'filter_regex'                         => '*\.php',
         'filter_is_file'                       => 1,
         'filter_is_dir'                        => 0
      ];

      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $pfCollect_File->add($input);
   }


   /**
    * @test
    */
   public function getSearchOptionsToAdd() {
      $pfCollect = new PluginFusioninventoryCollect();
      $sopts = $pfCollect->getSearchOptionsToAdd();

      $this->assertEquals(4, count($sopts));

      $expected = [
         'table'            => 'glpi_plugin_fusioninventory_collects_registries_contents',
         'field'            => 'value',
         'linkfield'        => '',
         'name'             => __('Registry', 'fusioninventory')." - Registry collection",
         'joinparams'       => ['jointype' => 'child'],
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => [
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_registries_id` = 1",
            'jointype'  => 'child'
         ]
      ];
      $this->assertEquals($expected, $sopts[5200]);

      $expected = [
         'table'            => 'glpi_plugin_fusioninventory_collects_wmis_contents',
         'field'            => 'value',
         'linkfield'        => '',
         'name'             => __('WMI', 'fusioninventory')." - WMI",
         'joinparams'       => ['jointype' => 'child'],
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => [
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_wmis_id` = 1",
            'jointype'  => 'child'
         ]
      ];
      $this->assertEquals($expected, $sopts[5201]);

      $expected = [
         'table'            => 'glpi_plugin_fusioninventory_collects_files_contents',
         'field'            => 'pathfile',
         'linkfield'        => '',
         'name'             => __('Find file', 'fusioninventory')." - PHP files".
            " - ".__('pathfile', 'fusioninventory'),
         'joinparams'       => ['jointype' => 'child'],
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => [
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = 1",
            'jointype'  => 'child'
         ]
      ];
      $this->assertEquals($expected, $sopts[5202]);

      $expected = [
         'table'            => 'glpi_plugin_fusioninventory_collects_files_contents',
         'field'            => 'size',
         'linkfield'        => '',
         'name'             => __('Find file', 'fusioninventory'). " - PHP files".
                                    " - ".__('Size', 'fusioninventory'),
         'joinparams'       => ['jointype' => 'child'],
         'datatype'         => 'text',
         'forcegroupby'     => true,
         'massiveaction'    => false,
         'nodisplay'        => true,
         'joinparams'       => [
            'condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = 1",
            'jointype'  => 'child'
         ]
      ];
      $this->assertEquals($expected, $sopts[5203]);
   }


   /**
    * @test
    */
   public function registryProcessWithAgent() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfCollect = new PluginFusioninventoryCollect();
      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $computer = new Computer();

      // Create a registry task with 2 paths to get
      $input = [
          'name'        => 'my registry keys',
          'entities_id' => 0,
          'type'        => 'registry',
          'is_active'   => 1
      ];
      $collects_id = $pfCollect->add($input);
      $this->assertEquals($collects_id, 1);

      $input = [
          'name' => 'Teamviewer',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'hive' => 'HKEY_LOCAL_MACHINE',
          'path' => '/software/Wow6432Node/TeamViewer/',
          'key'  => '*',
      ];
      $registry_tm = $pfCollect_Registry->add($input);
      $this->assertEquals($registry_tm, 1);

      $input = [
          'name' => 'FusionInventory',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'hive' => 'HKEY_LOCAL_MACHINE',
          'path' => '/software/FusionInventory-Agent/',
          'key'  => '*',
      ];
      $registry_fi = $pfCollect_Registry->add($input);
      $this->assertEquals($registry_fi, 2);

      // Create computer
      $input = [
          'name'        => 'pc01',
          'entities_id' => 0
      ];
      $computers_id = $computer->add($input);
      $this->assertEquals($computers_id, 1);

      $input = [
          'name'         => 'pc01',
          'entities_id'  => 0,
          'computers_id' => $computers_id,
          'device_id'    => 'pc01'
      ];
      $agents_id = $pfAgent->add($input);
      $this->assertEquals($agents_id, 1);

      // Create task
      $input = [
          'name'        => 'mycollect',
          'entities_id' => 0,
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertEquals($tasks_id, 1);

      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id' => 0,
          'name'    => 'collectjob',
          'method'  => 'collect',
          'targets' => exportArrayToDB([['PluginFusioninventoryCollect' => $collects_id]]),
          'actors'  => exportArrayToDB([['Computer' => $computers_id]]),
      ];
      $taskjobs_id = $pfTaskjob->add($input);
      $this->assertEquals($taskjobs_id, 1);
      $methods = [];
      foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
         $methods[] = $method['method'];
      }
      $pfTask->prepareTaskjobs($methods);
      $jobstates = $pfTaskjobstate->find();
      $this->assertEquals(1, count($jobstates));
      $jobstate = current($jobstates);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // Get jobs
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?action=getJobs&machineid=pc01");
      preg_match('/"token":"([a-z0-9]+)"/', $result, $matches);
      $this->assertEquals($result, '{"jobs":[{"function":"getFromRegistry","path":"HKEY_LOCAL_MACHINE\/software\/Wow6432Node\/TeamViewer\/*","uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_tm.'"},'
                                          . '{"function":"getFromRegistry","path":"HKEY_LOCAL_MACHINE\/software\/FusionInventory-Agent\/*","uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_fi.'"}],"postmethod":"POST","token":"'.$matches[1].'"}');
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 1
      $params = [
          'action'                => 'setAnswer',
          'InstallationDate'      => '2016-07-15',
          'Version'               => '11.0.62308',
          'UpdateVersion'         => '11.0.59518\0\0',
          'InstallationRev'       => '1110',
          '_cpt'                  => '1',
          'MIDInitiativeGUID'     => '{da2b3220-3d00-4f0f-93af-d38604c78405}',
          'ClientIC'              => '0x41A3B7BA',
          'uuid'                  => $jobstate['uniqid'],
          '_sid'                  => $registry_tm,
          'InstallationDirectory' => 'C:\\Program Files (x86)\\TeamViewer'
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 2
      $params = [
          'action'                  => 'setAnswer',
          'backend-collect-timeout' => 180,
          'httpd-port'              => '62354',
          'no-ssl-check'            => 1,
          'server'                  => 'http://10.0.2.2/glpi090/plugins/fusioninventory/',
          'logfile'                 => 'C:\\Program Files\\FusionInventory-Agent\\fusioninventory-agent.log',
          'timeout'                 => 180,
          'httpd-trust'             => '127.0.0.1/32',
          'uuid'                    => $jobstate['uniqid'],
          '_sid'                    => $registry_tm,
          '_cpt'                    => '1',
          'httpd-ip'                => '0.0.0.0',
          'logger'                  => 'File',
          'debug'                   => '1',
          'delaytime'               => '3600',
          'logfile-maxsize'         => '16'
      ];

      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // jobsdone
      $params = [
          'action' => 'jobsDone',
          'uuid'   => $jobstate['uniqid'],
          ];

      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


   /**
    * @test
    */
   public function wmiProcessWithAgent() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfCollect = new PluginFusioninventoryCollect();
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $pfCollect_Wmi_Content = new PluginFusioninventoryCollect_Wmi_Content();
      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $computer = new Computer();

      // Create a registry task with 2 paths to get
      $input = [
          'name'        => 'my wmi keys',
          'entities_id' => 0,
          'type'        => 'wmi',
          'is_active'   => 1
      ];
      $collects_id = $pfCollect->add($input);
      $this->assertEquals($collects_id, 1);

      $input = [
          'name'       => 'keyboad name',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'moniker'    => '',
          'class'      => 'Win32_Keyboard',
          'properties' => 'Name',
      ];
      $registry_kn = $pfCollect_Wmi->add($input);
      $this->assertEquals($registry_kn, 1);

      $input = [
          'name'       => 'keyboad description',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'moniker'    => '',
          'class'      => 'Win32_Keyboard',
          'properties' => 'Description',
      ];
      $registry_kd = $pfCollect_Wmi->add($input);
      $this->assertEquals($registry_kd, 2);

      // Create computer
      $input = [
          'name'        => 'pc01',
          'entities_id' => 0
      ];
      $computers_id = $computer->add($input);
      $this->assertEquals($computers_id, 1);

      $input = [
          'name'         => 'pc01',
          'entities_id'  => 0,
          'computers_id' => $computers_id,
          'device_id'    => 'pc01'
      ];
      $agents_id = $pfAgent->add($input);
      $this->assertEquals($agents_id, 1);

      // Create task
      $input = [
          'name'        => 'mycollect',
          'entities_id' => 0,
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertEquals($tasks_id, 1);

      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id' => 0,
          'name'    => 'collectjob',
          'method'  => 'collect',
          'targets' => exportArrayToDB([['PluginFusioninventoryCollect' => $collects_id]]),
          'actors'  => exportArrayToDB([['Computer' => $computers_id]]),
      ];
      $taskjobs_id = $pfTaskjob->add($input);
      $this->assertEquals($taskjobs_id, 1);
      $methods = [];
      foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
         $methods[] = $method['method'];
      }
      $pfTask->prepareTaskjobs($methods);
      $jobstates = $pfTaskjobstate->find();
      $this->assertEquals(1, count($jobstates));
      $jobstate = current($jobstates);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // Get jobs
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?action=getJobs&machineid=pc01");
      preg_match('/"token":"([a-z0-9]+)"/', $result, $matches);
      $this->assertEquals($result, '{"jobs":[{"function":"getFromWMI","class":"Win32_Keyboard","properties":["Name"],"uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_kn.'"},'
                                          . '{"function":"getFromWMI","class":"Win32_Keyboard","properties":["Description"],"uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_kd.'"}],"postmethod":"POST","token":"'.$matches[1].'"}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 1
      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_kn,
          '_cpt'   => '1',
          'Name'   => 'Enhanced (101- or 102-key)'
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 2
      $params = [
          'action'      => 'setAnswer',
          'uuid'        => $jobstate['uniqid'],
          '_sid'        => $registry_kd,
          '_cpt'        => '1',
          'Description' => 'Standard PS/2 Keyboard'
      ];

      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // jobsdone
      $params = [
          'action' => 'jobsDone',
          'uuid'   => $jobstate['uniqid'],
          ];

      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // check data in db
      $content = $pfCollect_Wmi_Content->find();
      $reference = [
          1 => [
              'id' => 1,
              'computers_id' => $computers_id,
              'plugin_fusioninventory_collects_wmis_id' => $registry_kn,
              'property'     => 'Name',
              'value'        => 'Enhanced (101- or 102-key)'
          ],
          2 => [
              'id' => 2,
              'computers_id' => $computers_id,
              'plugin_fusioninventory_collects_wmis_id' => $registry_kd,
              'property'     => 'Description',
              'value'        => 'Standard PS/2 Keyboard'
          ]
      ];
      $this->assertEquals($reference, $content);
   }


   /**
    * @test
    */
   public function filesProcessWithAgent() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfCollect = new PluginFusioninventoryCollect();
      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $pfCollect_File_Content = new PluginFusioninventoryCollect_File_Content();
      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $computer = new Computer();

      // Create a registry task with 2 paths to get
      $input = [
          'name'        => 'my files search',
          'entities_id' => 0,
          'type'        => 'file',
          'is_active'   => 1
      ];
      $collects_id = $pfCollect->add($input);
      $this->assertEquals($collects_id, 1);

      $input = [
          'name'           => 'desktop',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'dir'            => 'C:\Users\toto\Desktop',
          'limit'          => 10,
          'is_recursive'   => 1,
          'filter_is_file' => 1,
      ];
      $registry_desktop = $pfCollect_File->add($input);
      $this->assertEquals($registry_desktop, 1);

      $input = [
          'name'           => 'downloads',
          'plugin_fusioninventory_collects_id' => $collects_id,
          'dir'            => 'C:\Users\toto\Downloads',
          'limit'          => 10,
          'is_recursive'   => 1,
          'filter_is_file' => 1,
      ];
      $registry_down = $pfCollect_File->add($input);
      $this->assertEquals($registry_down, 2);

      // Create computer
      $input = [
          'name'        => 'pc01',
          'entities_id' => 0
      ];
      $computers_id = $computer->add($input);
      $this->assertEquals($computers_id, 1);

      $input = [
          'name'         => 'pc01',
          'entities_id'  => 0,
          'computers_id' => $computers_id,
          'device_id'    => 'pc01'
      ];
      $agents_id = $pfAgent->add($input);
      $this->assertEquals($agents_id, 1);

      // Create task
      $input = [
          'name'        => 'mycollect',
          'entities_id' => 0,
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertEquals($tasks_id, 1);

      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id' => 0,
          'name'    => 'collectjob',
          'method'  => 'collect',
          'targets' => exportArrayToDB([['PluginFusioninventoryCollect' => $collects_id]]),
          'actors'  => exportArrayToDB([['Computer' => $computers_id]]),
      ];
      $taskjobs_id = $pfTaskjob->add($input);
      $this->assertEquals($taskjobs_id, 1);
      $methods = [];
      foreach (PluginFusioninventoryStaticmisc::getmethods() as $method) {
         $methods[] = $method['method'];
      }
      $pfTask->prepareTaskjobs($methods);
      $jobstates = $pfTaskjobstate->find();
      $this->assertEquals(1, count($jobstates));
      $jobstate = current($jobstates);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // Get jobs
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?action=getJobs&machineid=pc01");
      preg_match('/"token":"([a-z0-9]+)"/', $result, $matches);
      $this->assertEquals($result, '{"jobs":[{"function":"findFile","dir":"C:Users\totoDesktop","limit":"10","recursive":"1","filter":{"is_file":"1","is_dir":"0"},"uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_desktop.'"},'
                                          . '{"function":"findFile","dir":"C:Users\totoDownloads","limit":"10","recursive":"1","filter":{"is_file":"1","is_dir":"0"},"uuid":"'.$jobstate['uniqid'].'","_sid":"'.$registry_down.'"}],"postmethod":"POST","token":"'.$matches[1].'"}');
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 1
      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_desktop,
          '_cpt'   => '3',
          'path'   => 'C:\\Users\\toto\\Desktop/06_import_tickets.php',
          'size'   => 5053
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_desktop,
          '_cpt'   => '2',
          'path'   => 'C:\\Users\\toto\\Desktop/fusioninventory.txt',
          'size'   => 28
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_desktop,
          '_cpt'   => '1',
          'path'   => 'C:\\Users\\toto\\Desktop/desktop.ini',
          'size'   => 282
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // answer 2
      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_down,
          '_cpt'   => '2',
          'path'   => 'C:\\Users\\toto\\Downloads/jxpiinstall.exe',
          'size'   => 738368
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $params = [
          'action' => 'setAnswer',
          'uuid'   => $jobstate['uniqid'],
          '_sid'   => $registry_down,
          '_cpt'   => '1',
          'path'   => 'C:\\Users\\toto\\Downloads/npp.6.9.2.Installer.exe',
          'size'   => 4211112
      ];
      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // jobsdone
      $params = [
          'action' => 'jobsDone',
          'uuid'   => $jobstate['uniqid'],
          ];

      $result = file_get_contents("http://localhost:8088/plugins/fusioninventory/b/collect/index.php?".http_build_query($params));
      $this->assertEquals($result, '{}');

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      // check data in db
      $content = $pfCollect_File_Content->find();
      $reference = [
          1 => [
              'id' => '1',
              'computers_id' => "$computers_id",
              'plugin_fusioninventory_collects_files_id' => "$registry_desktop",
              'pathfile'     => 'C:/Users/toto/Desktop/06_import_tickets.php',
              'size'         => '5053'
          ],
          2 => [
              'id' => '2',
              'computers_id' => "$computers_id",
              'plugin_fusioninventory_collects_files_id' => "$registry_desktop",
              'pathfile'     => 'C:/Users/toto/Desktop/fusioninventory.txt',
              'size'         => '28'
          ],
          3 => [
              'id' => '3',
              'computers_id' => "$computers_id",
              'plugin_fusioninventory_collects_files_id' => "$registry_desktop",
              'pathfile'     => 'C:/Users/toto/Desktop/desktop.ini',
              'size'         => '282'
          ],
          4 => [
              'id' => '4',
              'computers_id' => "$computers_id",
              'plugin_fusioninventory_collects_files_id' => "$registry_down",
              'pathfile'     => 'C:/Users/toto/Downloads/jxpiinstall.exe',
              'size'         => '738368'
          ],
          5 => [
              'id' => '5',
              'computers_id' => "$computers_id",
              'plugin_fusioninventory_collects_files_id' => "$registry_down",
              'pathfile'     => 'C:/Users/toto/Downloads/npp.6.9.2.Installer.exe',
              'size'         => '4211112'
          ]
      ];
      $this->assertEquals($reference, $content);
   }


   /**
    * @test
    */
   public function testFilesCleanComputer() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollect = new PluginFusioninventoryCollect();
      $input = [
         'name'         => 'Files collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
         'name'                                 => 'PHP files',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'dir'                                  => '/var/www',
         'is_recursive'                         => 1,
         'filter_regex'                         => '*\.php',
         'filter_is_file'                       => 1,
         'filter_is_dir'                        => 0
      ];
      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $file_id = $pfCollect_File->add($input);
      $input = [
         'computers_id'                                     => '1',
         'plugin_fusioninventory_collects_registries_id'    => $file_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->add($input);

      //First, check if file contents does exist
      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_File_Contents->fields));

      //Second, clean and check if it has been removed
      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->cleanComputer(1);

      $pfCollect_File_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_File_Contents->fields));
   }


   /**
    * @test
    */
   public function testRegistryCleanComputer() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollect = new PluginFusioninventoryCollect();
      $input = [
         'name'         => 'Registry collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
         'name'                                 => 'Registry collection',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'hive'                                 => 'HKEY_LOCAL_MACHINE',
         'path'                                 => '/',
         'key'                                  => 'daKey'
      ];
      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $registry_id = $pfCollect_Registry->add($input);
      $input = [
         'computers_id'                                     => '1',
         'plugin_fusioninventory_collects_registries_id'    => $registry_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->add($input);

      //First, check if registry contents does exist
      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_Registry_Contents->fields));

      //Second, clean and check if it has been removed
      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->cleanComputer(1);

      $pfCollect_Registry_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_Registry_Contents->fields));
   }


   /**
    * @test
    */
   public function testWmiCleanComputer() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollect = new PluginFusioninventoryCollect();
      $input = [
         'name'         => 'WMI collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
          'name'                                => 'WMI',
          'plugin_fusioninventory_collects_id'  => $collects_id,
          'moniker'                             => 'DaWMI'
      ];
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $wmi_id = $pfCollect_Wmi->add($input);
      $input = [
         'computers_id'                                     => '1',
         'plugin_fusioninventory_collects_registries_id'    => $wmi_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->add($input);

      //First, check if wmi contents does exist
      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_Wmi_Contents->fields));

      //Second, clean and check if it has been removed
      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->cleanComputer(1);

      $pfCollect_Wmi_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_Wmi_Contents->fields));
   }


   /**
    * @test
    */
   public function testDeleteComputer() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      // Create computer
      $computer = new Computer();
      $input = [
         'name'        => 'pc01',
         'entities_id' => 0
      ];

      $computers_id = $computer->add($input);
      $this->assertEquals($computers_id, 1);

      $pfCollect = new PluginFusioninventoryCollect();

      //populate wmi data
      $input = [
         'name'         => 'WMI collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
          'name'                                => 'WMI',
          'plugin_fusioninventory_collects_id'  => $collects_id,
          'moniker'                             => 'DaWMI'
      ];
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $wmi_id = $pfCollect_Wmi->add($input);
      $input = [
         'computers_id'                                     => $computers_id,
         'plugin_fusioninventory_collects_registries_id'    => $wmi_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->add($input);

      //check if wmi contents does exist
      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_Wmi_Contents->fields));

      //populate files data
      $input = [
         'name'         => 'Files collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
         'name'                                 => 'PHP files',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'dir'                                  => '/var/www',
         'is_recursive'                         => 1,
         'filter_regex'                         => '*\.php',
         'filter_is_file'                       => 1,
         'filter_is_dir'                        => 0
      ];
      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $file_id = $pfCollect_File->add($input);
      $input = [
         'computers_id'                                     => $computers_id,
         'plugin_fusioninventory_collects_registries_id'    => $file_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->add($input);

      //check if file contents does exist
      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_File_Contents->fields));

      //populate registry data
      $input = [
         'name'         => 'Registry collect',
         'entities_id'  => $_SESSION['glpiactive_entity'],
         'is_recursive' => '0',
         'type'         => 'registry',
         'is_active'    => 1
      ];
      $collects_id = $pfCollect->add($input);
      $input = [
         'name'                                 => 'Registry collection',
         'plugin_fusioninventory_collects_id'   => $collects_id,
         'hive'                                 => 'HKEY_LOCAL_MACHINE',
         'path'                                 => '/',
         'key'                                  => 'daKey'
      ];
      $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
      $registry_id = $pfCollect_Registry->add($input);
      $input = [
         'computers_id'                                     => $computers_id,
         'plugin_fusioninventory_collects_registries_id'    => $registry_id,
         'key'                                              => 'test_key',
         'value'                                            => 'test_value'
      ];
      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->add($input);

      //check if registry contents does exist
      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->getFromDB(1);

      $this->assertEquals(5, count($pfCollect_Registry_Contents->fields));

      //delete computer and check if it has been removed
      $computer->delete(['id' => $computers_id]);
      $this->assertTrue($computer->getFromDB($computers_id));

      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->getFromDB(1);
      $this->assertEquals(5, count($pfCollect_Wmi_Contents->fields));

      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->getFromDB(1);
      $this->assertEquals(5, count($pfCollect_Registry_Contents->fields));

      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->getFromDB(1);
      $this->assertEquals(5, count($pfCollect_File_Contents->fields));

      //purge computer and check if it has been removed
      $computer->delete(['id' => $computers_id], 1);
      $this->assertFalse($computer->getFromDB($computers_id));

      $pfCollect_Wmi_Contents = new PluginFusioninventoryCollect_Wmi_Content();
      $pfCollect_Wmi_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_Wmi_Contents->fields));

      $pfCollect_Registry_Contents = new PluginFusioninventoryCollect_Registry_Content();
      $pfCollect_Registry_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_Registry_Contents->fields));

      $pfCollect_File_Contents = new PluginFusioninventoryCollect_File_Content();
      $pfCollect_File_Contents->getFromDB(1);
      $this->assertEquals(0, count($pfCollect_File_Contents->fields));
   }


}
