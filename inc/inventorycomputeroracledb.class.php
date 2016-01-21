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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryComputerOracledb extends CommonDBTM {

   static $rightname ="computer";

   static function getTypeName($nb=0) {
      return __('Oracle', 'fusioninventory');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getType() == 'Computer') {
         if (self::canView()) {
            if (countElementsInTable($this->getTable(), 
"`computers_id`=".$item->getID()) > 0) {
               return self::createTabEntry(__('Oracle', 'fusioninventory'));
            }
          }
      }
      
      return '';
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
         $pfOracledb = new self();
    
      if (($item->getType() == 'Computer') && $item->getID() > 0) {
         $pfOracledb::showForComputer($item);
      }

      return TRUE;
   }

   /**
   * Display form for solaris zone
   *
   * @param $items_id integer ID of the zone
   *
   * @return bool TRUE if form is ok
   *
   **/
   static function showForComputer(Computer $computer) {

       $condition = "`computers_id`=".$computer->getID();
       $oracledbs = getAllDatasFromTable(self::getTable(), $condition); 
       
      if (empty($oracledbs)) {
	return;
      }
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
      echo "<tr>";
      echo "<th colspan='4'>".__('Oracle infos', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "</table>";

      foreach ($oracledbs as $db) {
	  echo "<table class='tab_cadre_fixe' cellpadding='1'>";
	  echo "<tr>";
	  echo "<th colspan='4'>".$db['name']."</th>";
	  echo "</tr>";

	  
	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Name')."</td>";
	  echo "<td>";
	  echo $db['name'];
	  echo "</td>";

	  echo "<td>".__('Version')."</td>";
	  echo "<td>";
	  echo $db['version'];
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Memory Target')."</td>";
	  echo "<td>";
	  echo $db['memory_target'];
	  echo "</td>";

	  echo "<td>".__('Sga Target')."</td>";
	  echo "<td>";
	  echo $db['sga_target'];
	  echo "</td></tr>";
	
	  echo "<tr>";
	  echo "<th colspan='4'>".__('Options', 'fusioninventory')."</th>";
	  echo "</tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Change management pack')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_change_management_pack']);
	  echo "</td>";

	  echo "<td>".__('Advanced compression')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_advanced_compression']);
	  echo "</td></tr>";
	  
	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Configuration management')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_configuration_management']);
	  echo "</td>";

	  echo "<td>".__('Active Data Guard')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_active_data_guard']);
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Data Masking pack')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_data_masking_pack']);
	  echo "</td>";

	  echo "<td>".__('Data Mining')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_data_mining']);
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Data Vault')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_data_vault']);
	  echo "</td>";

	  echo "<td>".__('Diagnostic Pack')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_diagnostic_pack']);
	  echo "</td></tr>";
	 
	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Exadata')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_exadata']);
	  echo "</td>";

	  echo "<td>".__('Label Security')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_label_security']);
	  echo "</td></tr>";
	 
	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('OLAP')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_olap']);
	  echo "</td>";

	  echo "<td>".__('Partitionning')."</td>";
	  echo "<td>";
	  echo Dropdown::getYesNo($db['has_paritionning']);
	  echo "</td></tr>";

	 
	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Provisionning Patch Automation Pack')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_provisionning_patch_automation_pack']);
	  echo "</td>";

	  echo "<td>".__('Provisionning Patch Automation Pack for 
Database')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_provisionning_patch_automation_pack_for_database']);
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Real Application Cluster')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_real_application_cluster']);
	  echo "</td>";

	  echo "<td>".__('Real Application Testing')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_real_application_testing']);
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Spatial')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_spatial']);
	  echo "</td>";

	  echo "<td>".__('Total Recall')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_total_recall']);
	  echo "</td></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Tuning Pack')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_tuning_pack']);
	  echo "</td>";

	  echo "<td>".__('Weblogic Server Management Pack Enterprise 
Edition')."</td>";
	  echo "<td>";
	  echo 
Dropdown::getYesNo($db['has_weblogic_server_management_pack']);
	  echo "</td></tr>";

	  echo "</table>";
      }

      return TRUE;
   }



   /**
   * Delete solariszone on computer
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $oracle = new self();
      $oracle->deleteByCriteria(array('computers_id' 
                                         => $items_id));
   }
}

?>
