<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpStateDiscovery extends CommonDBTM {
   

   function canView() {
      return true;
   }

   
   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['agents'][28];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'id';
		$tab[1]['linkfield'] = 'id';
		$tab[1]['name'] = 'id';
		$tab[1]['datatype'] = 'number';

		$tab[2]['table'] = "glpi_plugin_fusioninventory_agents";
		$tab[2]['field'] = 'name';
		$tab[2]['linkfield'] = 'plugin_fusioninventory_agents_id';
		$tab[2]['name'] = $LANG['plugin_fusioninventory']['agents'][28];
		$tab[2]['datatype'] = 'itemlink';
      $tab[2]['itemlink_type']  = 'PluginFusioninventoryAgent';

//		$tab[3]['table'] = "glpi_plugin_fusioninventory_taskjobs";
//		$tab[3]['field'] = 'name';
//		$tab[3]['linkfield'] = 'plugin_fusioninventory_taskjob_id';
//		$tab[3]['name'] = 'task job';
//		$tab[3]['datatype'] = 'itemlink';
//		$tab[3]['itemlink_type']  = 'PluginFusioninventoryTaskjob';
//
		$tab[4]['table'] = 'glpi_plugin_fusioninventory_taskjobstatus';
		$tab[4]['field'] = 'state';
		$tab[4]['linkfield'] = 'plugin_fusioninventory_taskjob_id';
		$tab[4]['name'] = $LANG['joblist'][0];
		$tab[4]['datatype'] = 'number';
      $tab[4]['itemlink_type']  = 'glpi_plugin_fusioninventory_taskjobstatus';

		$tab[5]['table'] = $this->getTable();
		$tab[5]['field'] = 'start_time';
		$tab[5]['linkfield'] = 'start_time';
		$tab[5]['name'] = $LANG['plugin_fusinvsnmp']['state'][4];
		$tab[5]['datatype'] = 'datetime';

		$tab[6]['table'] = $this->getTable();
		$tab[6]['field'] = 'end_time';
		$tab[6]['linkfield'] = 'end_time';
		$tab[6]['name'] = $LANG['plugin_fusinvsnmp']['state'][5];
		$tab[6]['datatype'] = 'datetime';

		$tab[7]['table'] = $this->getTable();
		$tab[7]['field'] = 'threads';
		$tab[7]['linkfield'] = 'threads';
		$tab[7]['name'] = $LANG['plugin_fusinvsnmp']['agents'][24];
		$tab[7]['datatype'] = 'number';

		$tab[8]['table'] = $this->getTable();
		$tab[8]['field'] = 'nb_ip';
		$tab[8]['linkfield'] = 'nb_ip';
		$tab[8]['name'] = $LANG['plugin_fusinvsnmp']['processes'][37];
		$tab[8]['datatype'] = 'number';

		$tab[9]['table'] = $this->getTable();
		$tab[9]['field'] = 'nb_found';
		$tab[9]['linkfield'] = 'nb_found';
		$tab[9]['name'] = $LANG['plugin_fusinvsnmp']['state'][6];
		$tab[9]['datatype'] = 'number';

		$tab[10]['table'] = $this->getTable();
		$tab[10]['field'] = 'nb_error';
		$tab[10]['linkfield'] = 'nb_error';
		$tab[10]['name'] = $LANG['plugin_fusinvsnmp']['state'][7];
		$tab[10]['datatype'] = 'number';

		$tab[11]['table'] = $this->getTable();
		$tab[11]['field'] = 'nb_exists';
		$tab[11]['linkfield'] = 'nb_exists';
		$tab[11]['name'] = 'Devices existent';
		$tab[11]['datatype'] = 'number';

		$tab[12]['table'] = $this->getTable();
		$tab[12]['field'] = 'nb_import';
		$tab[12]['linkfield'] = 'nb_import';
		$tab[12]['name'] = 'devices imported';
		$tab[12]['datatype'] = 'number';
      
      return $tab;
   }


   
   function updateState($p_number, $a_input, $agent_id) {
      $data = $this->find("`plugin_fusioninventory_taskjob_id`='".$p_number."'
                              AND `plugin_fusioninventory_agents_id`='".$agent_id."'");
      if (count($data) == "0") {
         $input = array();
         $input['plugin_fusioninventory_taskjob_id'] = $p_number;
         $input['plugin_fusioninventory_agents_id'] = $agent_id;
         $id = $this->add($input);
         $this->getFromDB($id);
         $data[$id] = $this->fields;
      }
      
      foreach ($data as $process_id=>$input) {
         foreach ($a_input as $field=>$value) {
            if ($field == 'nb_ip'
                    || $field == 'nb_found'
                    || $field == 'nb_error'
                    || $field == 'nb_exists'
                    || $field == 'nb_import') {

                $input[$field] = $data[$process_id][$field] + $value;
             } else {
                $input[$field] = $value;
            }
         }
         $this->update($input);
      }
      // If discovery and query are finished, we will end Process
      $this->getFromDB($process_id);
      $doEnd = 1;
      if (($this->fields['threads'] != '0') AND ($this->fields['end_time'] == '0000-00-00 00:00:00')) {
         $doEnd = 0;
      }

      if ($doEnd == '1') {
         $this->endState($p_number, date("Y-m-d H:i:s"), $agent_id);
      }
   }


   function endState($p_number, $date_end, $agent_id) {
      $data = $this->find("`plugin_fusioninventory_taskjob_id`='".$p_number."'
                              AND `plugin_fusioninventory_agents_id`='".$agent_id."'");
      foreach ($data as $process_id=>$input) {
         $input['end_time'] = $date_end;
         $this->update($input);
      }
   }

}

?>