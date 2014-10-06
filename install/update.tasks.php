<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the termas of the GNU Affero General Public License as published by
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
   @co-author Kevin Roy
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

function pluginFusioninventoryUpdateTasks( $migration , $plugin_id) {

   global $DB;
   /*
    * Table glpi_plugin_fusioninventory_tasks
    */
   $table = array();
   $table['name'] = 'glpi_plugin_fusioninventory_tasks';
   $table['oldname'] = array();

   $table['fields']  = array(
      'id' => array(
         'type'    => 'autoincrement',
         'value'   => ''
      ),
      'entities_id' => array(
         'type'    => 'integer',
         'value'   => NULL
      ),
      'name' => array(
         'type'    => 'string',
         'value'   => NULL
      ),
      'date_creation' => array(
         'type'    => 'datetime',
         'value'   => NULL
      ),
      'comment'    => array(
         'type'    => 'text',
         'value'   => NULL
      ),
      'is_active'  => array(
         'type'    => 'bool',
         'value'   => NULL
      ),
      'datetime_start' => array(
         'type'    => 'datetime',
         'value'   => NULL
      ),
      'datetime_end' => array(
         'type'    => 'datetime',
         'value'   => NULL
      ),
      'plugin_fusioninventory_timeslots_id' => array(
         'type'    => 'integer',
         'value'   => NULL
      ),
   );

   $table['oldfields']  = array(
      "communication",
      "permanent",
      "periodicity_count",
      "periodicity_type",
      "execution_id",
      "is_advancedmode"
   );

   $table['renamefields'] = array(
      'date_scheduled' => 'datetime_start'
   );

   $table['keys']   = array();
   $table['keys'][] = array('field' => 'entities_id', 'name' => '', 'type' => 'INDEX');
   $table['keys'][] = array('field' => 'is_active', 'name' => '', 'type' => 'INDEX');

   $table['oldkeys'] = array();

   migrateTablesFusionInventory($migration, $table);



   /*
    * Table glpi_plugin_fusioninventory_taskjobs
    */
   $table = array();
   $table['name'] = 'glpi_plugin_fusioninventory_taskjobs';
   $table['oldname'] = array();

   $table['oldfields'] = array(
      'retry_nb',
      'retry_time',
      'plugins_id',
      'users_id',
      'status',
      'statuscomment',
      'periodicity_count',
      'periodicity_type',
      'execution_id',
      'ranking'
   );

   $table['renamefields'] = array(
      'definition' => 'targets',
      'action' => 'actors'
   );

   $table['fields']  = array(
      'id' => array(
         'type'    => 'autoincrement',
         'value'   => ''
      ),
      'plugin_fusioninventory_tasks_id' => array(
         'type'    => 'integer',
         'value'   => NULL
      ),
      'entities_id' => array(
         'type'    => 'integer',
         'value'   => NULL
      ),
      'name' => array(
         'type'    => 'string',
         'value'   => NULL
      ),
      'date_creation' => array(
         'type'    => 'datetime',
         'value'   => NULL
      ),
      'method' => array(
         'type'    => 'string',
         'value'   => NULL
      ),
      'targets' => array(
         'type'    => 'text',
         'value'   => NULL
      ),
      'actors' => array(
         'type'    => 'text',
         'value'   => NULL
      ),
      'comment' => array(
         'type'    => 'text',
         'value'   => NULL
      )
   );


   $table['keys']   = array();
   $table['keys'][] = array(
      'field' => 'plugin_fusioninventory_tasks_id',
      'name' => '', 'type' => 'INDEX'
   );
   $table['keys'][] = array(
      'field' => 'entities_id',
      'name' => '',
      'type' => 'INDEX'
   );
   $table['keys'][] = array(
      'field' => 'method',
      'name' => '',
      'type' => 'INDEX'
   );

   $table['oldkeys'] = array(
      'plugins_id',
      'users_id',
      'rescheduled_taskjob_id'
   );

   migrateTablesFusionInventory($migration, $table);

   // * Update method name changed
   $DB->query("UPDATE `glpi_plugin_fusioninventory_taskjobs`
      SET `method`='InventoryComputerESX'
      WHERE `method`='ESX'");
   $DB->query("UPDATE `glpi_plugin_fusioninventory_taskjobs`
      SET `method`='networkinventory'
      WHERE `method`='snmpinventory'");
   $DB->query("UPDATE `glpi_plugin_fusioninventory_taskjobs`
      SET `method`='networkdiscovery'
      WHERE `method`='netdiscovery'");



   /*
    * Table glpi_plugin_fusioninventory_taskjoblogs
    */
   $table = array();
   $table['name'] = 'glpi_plugin_fusioninventory_taskjoblogs';
   $table['oldname'] = array();

   $table['fields']  = array(
      'id' => array(
         'type' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
         'value' => ''
      ),
      'plugin_fusioninventory_taskjobstates_id' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'date' => array(
         'type' => 'datetime',
         'value' => NULL
      ),
      'items_id' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'itemtype' => array(
         'type' => 'varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL',
         'value' => NULL
      ),
      'state' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'comment' => array(
         'type' => 'text',
         'value' => NULL
      )
   );

   $table['oldfields']  = array();

   $table['renamefields'] = array(
      'plugin_fusioninventory_taskjobstatus_id' => 'plugin_fusioninventory_taskjobstates_id'
   );

   $table['keys']   = array(
      array('field' => array('plugin_fusioninventory_taskjobstates_id', 'state', 'date'),
      'name' => 'plugin_fusioninventory_taskjobstates_id', 'type' => 'INDEX')
   );

   $table['oldkeys'] = array(
      'plugin_fusioninventory_taskjobstatus_id'
   );

   migrateTablesFusionInventory($migration, $table);

   // rename comments for new lang system (gettext in 0.84)
   $texts = array(
      'fusinvsnmp::1' => 'devicesqueried',
      'fusinvsnmp::2' => 'devicesfound',
      'fusinvsnmp::3' => 'diconotuptodate',
      'fusinvsnmp::4' => 'addtheitem',
      'fusinvsnmp::5' => 'updatetheitem',
      'fusinvsnmp::6' => 'inventorystarted',
      'fusinvsnmp::7' => 'detail',
      'fusioninventory::1' => 'badtoken',
      'fusioninventory::2' => 'agentcrashed',
      'fusioninventory::3' => 'importdenied'
   );
   $query = "SELECT * FROM `".$table['name']."`
      WHERE `comment` LIKE '%==%'";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      $comment = $data['comment'];
      foreach ($texts as $key=>$value) {
         $comment = str_replace("==".$key."==", "==".$value."==", $comment);
      }
      $DB->query("UPDATE `".$table['name']."`
         SET `comment`='".$DB->escape($comment)."'
         WHERE `id`='".$data['id']."'");
   }

   /*
    * Table glpi_plugin_fusioninventory_taskjobstates
    */
   $table = array();
   $table['name'] = 'glpi_plugin_fusioninventory_taskjobstates';
   $table['oldname'] = array(
      'glpi_plugin_fusioninventory_taskjobstatus'
   );

   $table['fields'] = array(
      'id' => array(
         'type' => 'bigint(20) not null auto_increment',
         'value' => '0'
      ),
      'plugin_fusioninventory_taskjobs_id' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'items_id' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'itemtype' => array(
         'type' => 'varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL',
         'value' => null
      ),
      'plugin_fusioninventory_agents_id' => array(
         'type' => 'integer',
         'value' => NULL
      ),
      'specificity' => array(
         'type' => 'text',
         'value' => null
      ),
      'uniqid' => array(
         'type' => 'string',
         'value' => null
      ),
      'state' => array(
         'type' => 'integer',
         'value' => NULL
      )
   );

   $table['renamefields'] = array();
   $table['oldfields'] = array(
      'execution_id'
   );

   $table['keys'] = array(
      array(
         'field' => array(
            'plugin_fusioninventory_taskjobs_id'
         ),
         'name' => '', 'type' => 'INDEX'
      ),
      array(
         'field' => array(
            'plugin_fusioninventory_agents_id',
            'state'
         ),
         'name' => '', 'type' => 'INDEX'
      ),
      array(
         'field' => array(
            'plugin_fusioninventory_agents_id',
            'plugin_fusioninventory_taskjobs_id',
            'items_id',
            'itemtype',
            'id',
            'state'
         ),
         'name' => 'plugin_fusioninventory_agents_items_states',
         'type' => 'INDEX'
      ),
   );
   $table['oldkeys'] = array();
   migrateTablesFusionInventory($migration, $table);

}
