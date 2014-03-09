<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */


class PluginFusioninventoryTask extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_task';

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Task management', 'fusioninventory');
   }



   function getSearchOptions() {

      $sopt = array();

      $sopt['common'] = __('Task');


      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = 'name';
      $sopt[1]['name']           = __('Name');

      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'date_scheduled';
      $sopt[2]['linkfield']      = 'date_scheduled';
      $sopt[2]['name']           = __('Scheduled date', 'fusioninventory');

      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = 'glpi_entities';
      $sopt[3]['field']          = 'completename';
      $sopt[3]['linkfield']      = 'entities_id';
      $sopt[3]['name']           = __('Entity');


      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'comment';
      $sopt[4]['linkfield']      = 'comment';
      $sopt[4]['name']           = __('Comments');


      $sopt[5]['table']          = $this->getTable();
      $sopt[5]['field']          = 'is_active';
      $sopt[5]['linkfield']      = 'is_active';
      $sopt[5]['name']           = __('Active');

      $sopt[5]['datatype']       = 'bool';

      $sopt[6]['table']          = $this->getTable();
      $sopt[6]['field']          = 'communication';
      $sopt[6]['linkfield']      = '';
      $sopt[6]['name']           = __('Communication type', 'fusioninventory');


      $sopt[8]['table']          = 'glpi_plugin_fusioninventory_taskjoblogs';
      $sopt[8]['field']          = 'state';
      $sopt[8]['name']           = 'Running';

      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = __('ID');
      $sopt[30]['datatype']      = 'number';

      return $sopt;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $CFG_GLPI;
      $tab_names = array();
      if ( self::can("task", "r") ) {
         if ($item->getType() == 'Computer') {
            $tab_names[] = __('FusInv', 'fusioninventory').' '. _n('Task', 'Tasks', 2);
         }

      }

      if (!empty($tab_names)) {
         return $tab_names;
      } else {
         return '';
      }
   }

   function defineTabs($options=array()){
      global $CFG_GLPI;
      $ong = array();

      /*
       * TODO: The "All" tab is malfunctionning and i had no other choice but to disable it.
       * This is not crucial at the moment and should be reconsidered when refactoring Tasks.
       */
      $ong['no_all_tab'] = TRUE;
      //Tabs in this form are handled by TaskJob class
      $this->addStandardTab('PluginFusioninventoryTaskJob', $ong, $options);

      return $ong;
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      if ($item->getType() == 'Computer') {

         // Possibility to remote agent
         if (PluginFusioninventoryTaskjob::isAllowurlfopen(1)) {
            $pfAgent = new PluginFusioninventoryAgent();
            if ($pfAgent->getAgentWithComputerid($item->fields['id'])) {
               $pfAgent->showRemoteStatus($item);
            }
         }
      }
   }

   /**
   * Purge task and taskjob
   *
   * @param $parm object to purge
   *
   * @return nothing
   *
   **/
   static function purgeTask($parm) {
      // $parm["id"]
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      // all taskjobs
      $a_taskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$parm->fields["id"]."'");
      foreach($a_taskjobs as $a_taskjob) {
         $pfTaskjob->delete($a_taskjob, 1);
      }
   }

   /**
   * Purge task and taskjob related with method
   *
   * @param $method value name of the method
   *
   * @return nothing
   *
   **/
   static function cleanTasksbyMethod($method) {
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTask = new PluginFusioninventoryTask();

      $a_taskjobs = $pfTaskjob->find("`method`='".$method."'");
      $task_id = 0;
      foreach($a_taskjobs as $a_taskjob) {
         $pfTaskjob->delete($a_taskjob, 1);
         if (($task_id != $a_taskjob['plugin_fusioninventory_tasks_id'])
            AND ($task_id != '0')) {

            // Search if this task have other taskjobs, if not, we will delete it
            $findtaskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
            if (count($findtaskjobs) == '0') {
               $pfTask->delete(array('id'=>$task_id), 1);
            }
         }
         $task_id = $a_taskjob['plugin_fusioninventory_tasks_id'];
      }
      if ($task_id != '0') {

         // Search if this task have other taskjobs, if not, we will delete it
         $findtaskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'");
         if (count($findtaskjobs) == '0') {
            $pfTask->delete(array('id'=>$task_id), 1);
         }
      }
   }


   function getTasksRunning($tasks_id=0) {
      global $DB;

      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'task');
      if ($tasks_id > 0) {
         $where = " AND task.`id`='".$tasks_id."'
            LIMIT 1 ";
      }

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks` as task
         WHERE execution_id !=
            (SELECT execution_id FROM glpi_plugin_fusioninventory_taskjobs as taskjob
               WHERE taskjob.`plugin_fusioninventory_tasks_id`=task.`id`
               ORDER BY execution_id DESC
               LIMIT 1
            )".$where;
      return $DB->query($query);
   }



   function getTasksPlanned($tasks_id=0) {
      global $DB;

      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'task');
      if ($tasks_id > 0) {
         $where = " AND task.`id`='".$tasks_id."'
            LIMIT 1 ";
      }

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_tasks` as task
         WHERE execution_id =
            (SELECT execution_id FROM glpi_plugin_fusioninventory_taskjobs as taskjob
               WHERE taskjob.`plugin_fusioninventory_tasks_id`=task.`id`
               ORDER BY execution_id DESC
               LIMIT 1
            )
            AND `is_active`='1'
            AND `periodicity_count` > 0
            AND `periodicity_type` != '0' ".$where;
      return $DB->query($query);
   }



   /**
   *  Get tasks filtered by relevant criterias
   *  @param $filter criterias to filter in the request
   **/
   static function getItemsFromDB($filter) {

      global $DB;
      $select = array("tasks"=>"task.*");
      $where = array();
      $leftjoin = array();

      // Filter active tasks
      if (     isset($filter['is_active'])
            && is_bool($filter['is_active']) ) {
         $where[] = "task.`is_active` = " . $filter['is_active'];
      }

      //Filter by running taskjobs
      if (     isset( $filter['is_running'] )
            && is_bool( $filter['is_running'] ) ) {
         //TODO: get running taskjobs
         if ( $filter['is_running'] ) {
            $where[] = "( task.`execution_id` != taskjob.`execution_id` )";
         } else {
            $where[] = "( task.`execution_id` = taskjob.`execution_id` )";
         }
         // add taskjobs table JOIN statement if not already set
         if ( !isset( $leftjoin['taskjobs'] ) ) {
               $leftjoin_bak = $leftjoin;
               $leftjoin_tmp = PluginFusioninventoryTaskJob::getJoinQuery();
               $leftjoin = array_merge( $leftjoin_bak, $leftjoin_tmp );
            if (!isset( $select["taskjobs"]) ) {
               $select['taskjobs'] = "taskjob.*";
            }
         }
      }

      //Filter by definition classes
      if (     isset($filter['definitions'])
            && is_array($filter['definitions']) ) {
         $where_tmp = array();
         //check classes existence and append them to the query filter
         foreach($filter['definitions'] as $itemclass => $itemid) {
            if ( class_exists($itemclass) ) {

               $cond = "taskjob.`definition` LIKE '%\"".$itemclass."\"";

               //adding itemid if not empty
               if (!empty($itemid)) {
                     $cond .= ":\"".$itemid."\"";
               }
               //closing LIKE statement
               $cond .= "%'";
               $where_tmp[] = $cond;
            }
         }
         //join every filtered conditions
         if( count($where_tmp) > 0) {
            // add taskjobs table JOIN statement if not already set
            if ( !isset( $leftjoin['taskjobs'] ) ) {
               $leftjoin_bak = $leftjoin;
               $leftjoin_tmp = PluginFusioninventoryTaskJob::getJoinQuery();
               $leftjoin = array_merge( $leftjoin_bak, $leftjoin_tmp );
            }
            if (!isset( $select["taskjobs"]) ) {
               $select['taskjobs'] = "taskjob.*";
            }
            $where[] = "( " . implode("OR", $where_tmp) . " )";
         }
      }

      //TODO: Filter by action classes

      //TODO: Filter by list of IDs
      if (     isset($filter['by_ids'])
            && is_bool($filter['by_entities']) ) {
      }

      // Filter by entity
      if (     isset($filter['by_entities'])
            && is_bool($filter['by_entities']) ) {
         $where[] = getEntitiesRestrictRequest( "", 'task' );
      }

      $results = NULL;
      $query =
         implode(
            "\n", array(
               "SELECT ".implode(',', $select),
               "FROM `glpi_plugin_fusioninventory_tasks` as task",
               implode("\n", $leftjoin),
               "WHERE\n    ".implode("\nAND ", $where)
            )
         );

      $results = array();
      $r = $DB->query($query);
      if ($r) {
         $results = PluginFusioninventoryToolbox::fetchAssocByTable($r);
      }

      return($results);
   }



   function getTasksInerror() {
      global $DB;

      $where = '';
      $where .= getEntitiesRestrictRequest("AND", 'glpi_plugin_fusioninventory_tasks');

      $query = "SELECT `glpi_plugin_fusioninventory_tasks`.*
         FROM `glpi_plugin_fusioninventory_tasks`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` AS taskjobs
            ON `plugin_fusioninventory_tasks_id` = `glpi_plugin_fusioninventory_tasks`.`id`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobstates` AS taskjobstates
            ON taskjobstates.`id` =
            (SELECT MAX(`id`)
             FROM glpi_plugin_fusioninventory_taskjobstates
             WHERE plugin_fusioninventory_taskjobs_id = taskjobs.`id`
             ORDER BY id DESC
             LIMIT 1
            )
         LEFT JOIN `glpi_plugin_fusioninventory_taskjoblogs`
            ON `glpi_plugin_fusioninventory_taskjoblogs`.`id` =
            (SELECT MAX(`id`)
            FROM `glpi_plugin_fusioninventory_taskjoblogs`
            WHERE `plugin_fusioninventory_taskjobstates_id`= taskjobstates.`id`
            ORDER BY id DESC LIMIT 1 )
         WHERE `glpi_plugin_fusioninventory_taskjoblogs`.`state`='4'
         ".$where."
         GROUP BY plugin_fusioninventory_tasks_id
         ORDER BY `glpi_plugin_fusioninventory_taskjoblogs`.`date` DESC";



      return $DB->query($query);
   }


}

