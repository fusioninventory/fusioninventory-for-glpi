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
   @since     2015

   ------------------------------------------------------------------------
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
      $input = array(
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      );
      $pfTask->add($input);

      $running_tasks = $pfTask->getItemsFromDB(
         array(
            'is_running'  => TRUE,
            'is_active'   => TRUE
         )
      );

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $this->assertEquals(array(), $running_tasks, 'Not find task because not have job');
   }


}
?>
