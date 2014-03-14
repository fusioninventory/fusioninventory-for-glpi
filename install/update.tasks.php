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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

function pluginFusioninventoryUpdateTasks( $migration ) {

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
         //'communication' => array(
         //   'type'    => 'string',
         //   'value'   => 'push'
         //),
         //'permanent'  => array(
         //   'type'    => 'string',
         //   'value'   => NULL
         //),
         'datetime_start' => array(
            'type'    => 'datetime',
            'value'   => NULL
         ),
         'datetime_end' => array(
            'type'    => 'datetime',
            'value'   => NULL
         ),
         //'periodicity_count' => array(
         //   'type'    => "int(6) NOT NULL DEFAULT '0'",
         //   'value'   => NULL
         //),
         //'periodicity_type' => array(
         //   'type'    => 'string',
         //   'value'   => NULL
         //),
         //'execution_id' => array(
         //   'type'    => "bigint(20) NOT NULL DEFAULT '0'",
         //   'value'   => NULL
         //),
         //'is_advancedmode' => array(
         //   'type'    => 'bool',
         //   'value'   => NULL
         //),
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
      $a_table = array();
      $a_table['name'] = 'glpi_plugin_fusioninventory_taskjobs';
      $a_table['oldname'] = array();

      $a_table['fields']  = array();
      $a_table['fields']['id']         = array('type'    => 'autoincrement',
                                               'value'   => '');
      $a_table['fields']['plugin_fusioninventory_tasks_id']= array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['entities_id']= array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['name']       = array('type'    => 'string',
                                               'value'   => NULL);
      $a_table['fields']['date_creation'] = array('type'    => 'datetime',
                                                  'value'   => NULL);
      $a_table['fields']['retry_nb'] = array('type'    => "tinyint(2) NOT NULL DEFAULT '0'",
                                               'value'   => NULL);
      $a_table['fields']['retry_time'] = array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['plugins_id'] = array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['method']     = array('type'    => 'string',
                                               'value'   => NULL);
      $a_table['fields']['definition'] = array('type'    => 'text',
                                               'value'   => NULL);
      $a_table['fields']['action']     = array('type'    => 'text',
                                               'value'   => NULL);
      $a_table['fields']['comment']    = array('type'    => 'text',
                                               'value'   => NULL);
      $a_table['fields']['users_id']   = array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['status']     = array('type'    => 'integer',
                                               'value'   => NULL);
      $a_table['fields']['rescheduled_taskjob_id'] = array('type'    => 'integer',
                                                           'value'   => NULL);
      $a_table['fields']['statuscomments'] = array('type'    => 'text',
                                                   'value'   => NULL);
      $a_table['fields']['periodicity_count'] = array('type'    => "int(6) NOT NULL DEFAULT '0'",
                                                      'value'   => NULL);
      $a_table['fields']['periodicity_type']  = array('type'    => 'string',
                                                      'value'   => NULL);
      $a_table['fields']['execution_id'] = array('type'    => "bigint(20) NOT NULL DEFAULT '0'",
                                                 'value'   => NULL);
      $a_table['fields']['ranking']    = array('type'    => 'integer',
                                               'value'   => NULL);

      $a_table['oldfields']  = array();

      $a_table['renamefields'] = array();

      $a_table['keys']   = array();
      $a_table['keys'][] = array('field' => 'plugin_fusioninventory_tasks_id',
                                 'name' => '', 'type' => 'INDEX');
      $a_table['keys'][] = array('field' => 'entities_id', 'name' => '', 'type' => 'INDEX');
      $a_table['keys'][] = array('field' => 'plugins_id' , 'name' => '', 'type' => 'INDEX');
      $a_table['keys'][] = array('field' => 'users_id'   , 'name' => '', 'type' => 'INDEX');
      $a_table['keys'][] = array('field' => 'rescheduled_taskjob_id',
                                                            'name' => '', 'type' => 'INDEX');
      $a_table['keys'][] = array('field' => 'method'      , 'name' => '', 'type' => 'INDEX');

      $a_table['oldkeys'] = array();

      migrateTablesFusionInventory($migration, $a_table);

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
      // * Update plugins_id
      $DB->query("UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `plugins_id`='".$plugins_id."'");



   /*
    * Table glpi_plugin_fusioninventory_taskjoblogs
    */
      $a_table = array();
      $a_table['name'] = 'glpi_plugin_fusioninventory_taskjoblogs';
      $a_table['oldname'] = array();

      $a_table['fields']  = array(
         'id'         => array('type' => 'BIGINT(20) NOT NULL AUTO_INCREMENT', 'value' => ''),
         'plugin_fusioninventory_taskjobstates_id' =>
                         array('type' => 'integer',                            'value' => NULL),
         'date'       => array('type' => 'datetime',                           'value' => NULL),
         'items_id'   => array('type' => 'integer',                            'value' => NULL),
         'itemtype'   => array('type' => 'varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL',
                                                                               'value' => NULL),
         'state'      => array('type' => 'integer',                            'value' => NULL),
         'comment'    => array('type' => 'text',                               'value' => NULL)
      );

      $a_table['oldfields']  = array();

      $a_table['renamefields'] = array(
         'plugin_fusioninventory_taskjobstatus_id' => 'plugin_fusioninventory_taskjobstates_id'
      );

      $a_table['keys']   = array(
         array('field' => array('plugin_fusioninventory_taskjobstates_id', 'state', 'date'),
               'name' => 'plugin_fusioninventory_taskjobstates_id', 'type' => 'INDEX')
      );

      $a_table['oldkeys'] = array(
         'plugin_fusioninventory_taskjobstatus_id'
      );

      migrateTablesFusionInventory($migration, $a_table);

      // rename comments for new lang system (gettext in 0.84)
         $a_text = array(
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
         $query = "SELECT * FROM `".$a_table['name']."`
            WHERE `comment` LIKE '%==%'";
         $result=$DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $comment = $data['comment'];
            foreach ($a_text as $key=>$value) {
               $comment = str_replace("==".$key."==", "==".$value."==", $comment);
            }
            $DB->query("UPDATE `".$a_table['name']."`
               SET `comment`='".$DB->escape($comment)."'
               WHERE `id`='".$data['id']."'");
         }

   /*
    * Table glpi_plugin_fusioninventory_taskjobstates
    */
      $newTable = "glpi_plugin_fusioninventory_taskjobstates";
      if (TableExists("glpi_plugin_fusioninventory_taskjobstatus")) {
         $migration->renameTable("glpi_plugin_fusioninventory_taskjobstatus", $newTable);
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` bigint(20) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_taskjobs_id",
                                 "plugin_fusioninventory_taskjobs_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "items_id",
                                 "items_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "state",
                                 "state",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "specificity",
                                 "specificity",
                                 "text DEFAULT NULL");
         $migration->changeField($newTable,
                                 "uniqid",
                                 "uniqid",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "bigint(20) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusioninventory_taskjobs_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "items_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "itemtype",
                              "varchar(100) DEFAULT NULL");
         $migration->addField($newTable,
                              "state",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "plugin_fusioninventory_agents_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "specificity",
                              "text DEFAULT NULL");
         $migration->addField($newTable,
                              "uniqid",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              "execution_id",
                              "bigint(20) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_taskjobs_id");
         $migration->addKey($newTable,
                            array("plugin_fusioninventory_agents_id", "state"),
                            "plugin_fusioninventory_agents_id");
      $migration->migrationOneTable($newTable);
      $DB->list_fields($newTable, FALSE);

}
