<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

class GetTaskByFiltersTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function GetTaskWithoutJobs() {
      global $DB;

      // Verify prepare a deploy task
      $DB->connect();

      $pfTask = new PluginFusioninventoryTask();

      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      ];
      $pfTask->add($input);

      $running_tasks = $pfTask->getItemsFromDB(
         [
            'is_running'  => true,
            'is_active'   => true
         ]
      );

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $this->assertEquals([], $running_tasks, 'Not find task because not have job');
   }


}
