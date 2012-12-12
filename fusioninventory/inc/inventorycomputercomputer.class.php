<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2012 FusionInventory team
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

class PluginFusioninventoryInventoryComputerComputer extends CommonDBTM {

   static function getTypeName($nb=0) {

      return "";
   }

   static function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   static function canView() {
      return Session::haveRight('computer', 'r');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $array_ret = array();
      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', "r")) {
            $a_computers = $this->find("`computers_id`='".$item->getID()."'", '', 1);
            if (count($a_computers) > 0) {
               // Bios/other informations
               $array_ret[0] = self::createTabEntry(__('Advanced informations', 'fusioninventory'));

            }

            $id = $item->getField('id');
            $folder = substr($id, 0, -1);
            if (empty($folder)) {
               $folder = '0';
            }
            if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id)) {
               $array_ret[1] = self::createTabEntry(__('Import informations', 'fusioninventory'));

            }
         }
      }
      return $array_ret;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfComputer = new self();
      if ($tabnum == '0') {
         if ($item->getID() > 0) {
            $pfComputer->showForm($item->getID());
         }
      }
      if ($tabnum == '1') {
         if ($item->getID() > 0) {
            $pfComputer->display_xml($item);

            $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
            $pfRulematchedlog->showForm($item->getID(), 'Computer');
         }
      }
      return true;
   }



   /**
    * Display informations about computer (bios...)
    *
    * @param type $computers_id
    */
   static function showInfo($item) {
     
      $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computerextend = current($pfInventoryComputerComputer->find(
                                              "`computers_id`='".$item->getID()."'",
                                              "", 1));
      if (empty($a_computerextend)) {
         return;
      }

      echo '<table class="tab_glpi">';
      echo '<tr>';
      echo '<th colspan="2">'.__('FusionInventory', 'fusioninventory').'</th>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Last inventory', 'fusioninventory');
      echo '</td>';
      echo '<td>';

      echo '</td>';
      echo '</tr>';

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->showInfoForComputer($item->getID());
      
      if ($a_computerextend['bios_date'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Date du BIOS', 'fusioninventory').'</td>';
         echo '<td>'.Html::convDate($a_computerextend['bios_date']).'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['bios_version'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Version du BIOS', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['bios_version'].'</td>';
         echo '</tr>';
      }

      if ($a_computerextend['bios_manufacturers_id'] > 0) {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Manufacturer').'&nbsp;:</td>';
         echo '<td>';
         echo Dropdown::getDropdownName("glpi_manufacturers", 
                                        $a_computerextend['bios_manufacturers_id']);
         echo '</td>';
         echo '</tr>';
      }

      if ($a_computerextend['operatingsystem_installationdate'] != '') {
         echo '<tr class="tab_bg_1">';
         echo "<td>".__('Operating system')." - ".__('Installation')." (".strtolower(__('Date')).")</td>";
         echo '<td>'.Html::convDate($a_computerextend['operatingsystem_installationdate']).'</td>';
         echo '</tr>';
      }
      
      if ($a_computerextend['winowner'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Owner', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['winowner'].'</td>';
         echo '</tr>';
      }
      
      if ($a_computerextend['wincompany'] != '') {
         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Company', 'fusioninventory').'</td>';
         echo '<td>'.$a_computerextend['wincompany'].'</td>';
         echo '</tr>';
      }

      echo '</table>';
   }



   function display_xml($item) {
      global $CFG_GLPI;

      $id = $item->getField('id');

      $folder = substr($id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id)) {
         // $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id);
         // $xml = str_replace("<", "&lt;", $xml);
         // $xml = str_replace(">", "&gt;", $xml);
         // $xml = str_replace("\n", "<br/>", $xml);
         echo "<table class='tab_cadre_fixe' cellpadding='1'>";
         echo "<tr>";
         echo "<th>".__('FusInv', 'fusioninventory')." ".
            __('XML', 'fusioninventory');

         echo " (".__('Last inventory', 'fusioninventory')."&nbsp;: " .
            Html::convDateTime(date("Y-m-d H:i:s",
                         filemtime(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id))).")";
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td width='130' align='center'>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/send_xml.php?pluginname=fusinvinventory&file=".$folder."/".$id."'>".__('Download')."</a>";
         echo "</td>";
         echo "</tr>";

         // echo "<tr class='tab_bg_1'>";
         // echo "<td>";
         // echo "<pre width='130'>".$xml."</pre>";
         // echo "</td>";
         // echo "</tr>";
         echo "</table>";
      }
   }
}

?>
