<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2023 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @author    Kevin Roy
 * @copyright Copyright (c) 2010-2023 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */


/**
 * Manage update the task system
 *
 * @global object $DB
 * @param object $migration
 * @param integer $plugin_id
 */
function pluginFusioninventoryUpdateTasks($migration, $plugin_id) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_tasks
    */
   $table = [];
   $table['name'] = 'glpi_plugin_fusioninventory_tasks';
   $table['oldname'] = [];

   $table['fields']  = [
      'id' => [
         'type'    => 'autoincrement',
         'value'   => ''
      ],
      'entities_id' => [
         'type'    => "int unsigned NOT NULL DEFAULT '0'",
         'value'   => null
      ],
      'name' => [
         'type'    => 'string',
         'value'   => null
      ],
      'date_creation' => [
         'type'    => 'timestamp NULL DEFAULT NULL',
         'value'   => null
      ],
      'comment'    => [
         'type'    => 'text',
         'value'   => null
      ],
      'is_active'  => [
         'type'    => "tinyint(1) NOT NULL DEFAULT '0'",
         'value'   => null
      ],
      'datetime_start' => [
         'type'    => 'timestamp NULL DEFAULT NULL',
         'value'   => null
      ],
      'datetime_end' => [
         'type'    => 'timestamp NULL DEFAULT NULL',
         'value'   => null
      ],
      'plugin_fusioninventory_timeslots_prep_id' => [
         'type'    => "int unsigned NOT NULL DEFAULT '0'",
         'value'   => null
      ],
      'plugin_fusioninventory_timeslots_exec_id' => [
         'type'    => "int unsigned NOT NULL DEFAULT '0'",
         'value'   => null
      ],
   ];

   $table['oldfields'] = [
      "communication",
      "permanent",
      "periodicity_count",
      "periodicity_type",
      "execution_id",
      "is_advancedmode"
   ];

   $table['renamefields'] = [
      'date_scheduled'                      => 'datetime_start',
      'plugin_fusioninventory_timeslots_id' => 'plugin_fusioninventory_timeslots_prep_id'
   ];

   $table['keys']   = [];
   $table['keys'][] = ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'];
   $table['keys'][] = ['field' => 'is_active', 'name' => '', 'type' => 'INDEX'];
   $table['keys'][] = ['field' => 'plugin_fusioninventory_timeslots_prep_id', 'name' => '', 'type' => 'INDEX'];
   $table['keys'][] = ['field' => 'plugin_fusioninventory_timeslots_exec_id', 'name' => '', 'type' => 'INDEX'];

   $table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $table);

   /*
    * Table glpi_plugin_fusioninventory_taskjobs
    */
   $table = [];
   $table['name'] = 'glpi_plugin_fusioninventory_taskjobs';
   $table['oldname'] = [];

   $table['oldfields'] = [
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
   ];

   $table['renamefields'] = [
      'definition' => 'targets',
      'action' => 'actors'
   ];

   $table['fields'] = [
      'id' => [
         'type'    => 'autoincrement',
         'value'   => ''
      ],
      'plugin_fusioninventory_tasks_id' => [
         'type'    => "int unsigned NOT NULL DEFAULT '0'",
         'value'   => null
      ],
      'entities_id' => [
         'type'    => "int unsigned NOT NULL DEFAULT '0'",
         'value'   => null
      ],
      'name' => [
         'type'    => 'string',
         'value'   => null
      ],
      'date_creation' => [
         'type'    => 'timestamp NULL DEFAULT NULL',
         'value'   => null
      ],
      'method' => [
         'type'    => 'string',
         'value'   => null
      ],
      'targets' => [
         'type'    => 'text',
         'value'   => null
      ],
      'actors' => [
         'type'    => 'text',
         'value'   => null
      ],
      'comment' => [
         'type'    => 'text',
         'value'   => null
      ]
   ];

   $table['keys']   = [];
   $table['keys'][] = [
      'field' => 'plugin_fusioninventory_tasks_id',
      'name' => '', 'type' => 'INDEX'
   ];
   $table['keys'][] = [
      'field' => 'entities_id',
      'name' => '',
      'type' => 'INDEX'
   ];
   $table['keys'][] = [
      'field' => 'method',
      'name' => '',
      'type' => 'INDEX'
   ];

   $table['oldkeys'] = [
      'plugins_id',
      'users_id',
      'rescheduled_taskjob_id'
   ];

   migrateTablesFusionInventory($migration, $table);

   // * Update method name changed
   $DB->update(
      'glpi_plugin_fusioninventory_taskjobs', [
         'method' => 'InventoryComputerESX'
      ], [
         'method' => 'ESX'
      ]
   );
   $DB->update(
      'glpi_plugin_fusioninventory_taskjobs', [
         'method' => 'networkinventory'
      ], [
         'method' => 'snmpinventory'
      ]
   );
   $DB->update(
      'glpi_plugin_fusioninventory_taskjobs', [
         'method' => 'networkdiscovery'
      ], [
         'method' => 'netdiscovery'
      ]
   );

   /*
    * Table glpi_plugin_fusioninventory_taskjoblogs
    */
   $table = [];
   $table['name'] = 'glpi_plugin_fusioninventory_taskjoblogs';
   $table['oldname'] = [];

   $table['fields']  = [
      'id' => [
         'type' => 'BIGINT unsigned NOT NULL AUTO_INCREMENT',
         'value' => ''
      ],
      'plugin_fusioninventory_taskjobstates_id' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'date' => [
         'type' => 'timestamp NULL DEFAULT NULL',
         'value' => null
      ],
      'items_id' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'itemtype' => [
         'type' => 'varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
         'value' => null
      ],
      'state' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'comment' => [
         'type' => 'text',
         'value' => null
      ]
   ];

   $table['oldfields']  = [];

   $table['renamefields'] = [
      'plugin_fusioninventory_taskjobstatus_id' => 'plugin_fusioninventory_taskjobstates_id'
   ];

   $table['keys']   = [
      ['field' => ['plugin_fusioninventory_taskjobstates_id', 'state', 'date'],
      'name' => 'plugin_fusioninventory_taskjobstates_id', 'type' => 'INDEX']
   ];

   $table['oldkeys'] = [
      'plugin_fusioninventory_taskjobstatus_id'
   ];

   migrateTablesFusionInventory($migration, $table);

   // rename comments for new lang system (gettext in 0.84)
   $texts = [
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
   ];

   $iterator = $DB->request([
      'FROM'   => $table['name'],
      'WHERE'  => ['comment' => ['LIKE', '%==%']]
   ]);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         $table['name'], [
            'comment'   => new \QueryParam()
         ], [
            'id'        => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      foreach ($iterator as $data) {
         $comment = $data['comment'];
         foreach ($texts as $key=>$value) {
            $comment = str_replace("==".$key."==", "==".$value."==", $comment);
         }

         $comment = $DB->escape($comment);
         $stmt->bind_param(
            'ss',
            $comment,
            $data['id']
         );
      }
      mysqli_stmt_close($stmt);
   }

   /*
    * Table glpi_plugin_fusioninventory_taskjobstates
    */
   $table = [];
   $table['name'] = 'glpi_plugin_fusioninventory_taskjobstates';
   $table['oldname'] = [
      'glpi_plugin_fusioninventory_taskjobstatus'
   ];

   $table['fields'] = [
      'id' => [
         'type' => 'bigint unsigned not null auto_increment',
         'value' => '0'
      ],
      'plugin_fusioninventory_taskjobs_id' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'items_id' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'itemtype' => [
         'type' => 'varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
         'value' => null
      ],
      'plugin_fusioninventory_agents_id' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'specificity' => [
         'type' => 'text',
         'value' => null
      ],
      'uniqid' => [
         'type' => 'string',
         'value' => null
      ],
      'state' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => null
      ],
      'date_start' => [
         'type' => 'timestamp NULL DEFAULT NULL',
         'value' => null
      ],
      'nb_retry' => [
         'type' => "int unsigned NOT NULL DEFAULT '0'",
         'value' => 0
      ],
      'max_retry' => [
         'type' => "int unsigned NOT NULL DEFAULT '1'",
         'value' => 1
      ]
   ];

   $table['renamefields'] = [];
   $table['oldfields'] = [
      'execution_id'
   ];

   $table['keys'] = [
      [
         'field' => [
            'plugin_fusioninventory_taskjobs_id'
         ],
         'name' => '', 'type' => 'INDEX'
      ],
      [
         'field' => [
            'plugin_fusioninventory_agents_id',
            'state'
         ],
         'name' => '', 'type' => 'INDEX'
      ],
      [
         'field' => [
            'plugin_fusioninventory_agents_id',
            'plugin_fusioninventory_taskjobs_id',
            'items_id',
            'itemtype',
            'id',
            'state'
         ],
         'name' => 'plugin_fusioninventory_agents_items_states',
         'type' => 'INDEX'
      ]

   ];
   $table['oldkeys'] = [];
   migrateTablesFusionInventory($migration, $table);

}
