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

class PluginFusinvinventoryComputer extends CommonDBTM {
   
   static function getTypeName() {

      return "";
   }

   function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   function canView() {
      return Session::haveRight('computer', 'r');
   }


   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      $array_ret = array();
      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', "r")) {
            $a_computers = $this->find("`computers_id`='".$item->getID()."'", '', 1);
            if (count($a_computers) > 0) {
               // Bios/other informations
               $array_ret[0] = self::createTabEntry($LANG['entity'][14]);
            }
            
            $id = $item->getField('id');
            $folder = substr($id, 0, -1);
            if (empty($folder)) {
               $folder = '0';
            }
            if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id)) {
               $array_ret[1] = self::createTabEntry($LANG['plugin_fusioninventory']['rules'][21]);
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
    * @global type $LANG
    * @param type $computers_id 
    */   
   function showForm($computers_id) {
      global $LANG;
      
      $a_computerextend = current($this->find("`computers_id`='".$computers_id."'", 
                                              "", 1));
      if (empty($a_computerextend)) {
         $this->getEmpty();
         $a_computerextend = $this->fields;
      }
      
      echo '<div align="center">';
      echo '<table class="tab_cadre_fixe" style="margin: 0; margin-top: 5px;">';
      echo '<tr>';
      echo '<th colspan="4">'.$LANG['entity'][14].'</th>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<th colspan="2" width="50%">'.$LANG['plugin_fusinvinventory']['bios'][0].'</th>';
      echo '<th colspan="2">'.$LANG['common'][67].'</th>';
      echo '</tr>';
      
      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['common'][27].'&nbsp;:</td>';
      echo '<td>'.Html::convDate($a_computerextend['bios_date']).'</td>';
      if (Html::convDate($a_computerextend['operatingsystem_installationdate']) == '') {
         echo "<td colspan='2'></td>";
      } else {
         echo "<td>".$LANG['computers'][9]." - ".$LANG['install'][3]." (".strtolower($LANG['common'][27]).")&nbsp;:</td>";
         echo '<td>'.Html::convDate($a_computerextend['operatingsystem_installationdate']).'</td>';
      }
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['rulesengine'][78].'&nbsp;:</td>';
      echo '<td>'.$a_computerextend['bios_version'].'</td>';
      if ($a_computerextend['winowner'] == '') {
         echo "<td colspan='2'></td>";
      } else {
         echo '<td>'.$LANG['plugin_fusinvinventory']['computer'][1].'&nbsp;:</td>';
         echo '<td>'.$a_computerextend['winowner'].'</td>';
      }
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['common'][5].'&nbsp;:</td>';
      echo '<td>';
      echo Dropdown::getDropdownName("glpi_manufacturers", $a_computerextend['bios_manufacturers_id']);
      echo '</td>';
      if ($a_computerextend['wincompany'] == '') {
         echo "<td colspan='2'></td>";
      } else {
         echo '<td>'.$LANG['plugin_fusinvinventory']['computer'][2].'&nbsp;:</td>';
         echo '<td>'.$a_computerextend['wincompany'].'</td>';
      }
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>Asset TAG&nbsp;:</td>';
      echo '<td>'.$a_computerextend['bios_assettag'].'</td>';
      echo "<td colspan='2'></td>";
      echo '</tr>';

      echo '</table>';
      echo '</div>';
      
   }
   
   
   
   function display_xml($item) {
      global $LANG,$CFG_GLPI;

      $id = $item->getField('id');

      $folder = substr($id, 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id)) {
//         $xml = file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id);
//         $xml = str_replace("<", "&lt;", $xml);
//         $xml = str_replace(">", "&gt;", $xml);
//         $xml = str_replace("\n", "<br/>", $xml);
         echo "<table class='tab_cadre_fixe' cellpadding='1'>";
         echo "<tr>";
         echo "<th>".$LANG['plugin_fusioninventory']['title'][1]." ".
            $LANG['plugin_fusioninventory']['xml'][0];
         echo " (".$LANG['plugin_fusinvinventory']['computer'][0]."&nbsp;: " . 
            Html::convDateTime(date("Y-m-d H:i:s", 
                         filemtime(GLPI_PLUGIN_DOC_DIR."/fusinvinventory/".$folder."/".$id))).")";
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td width='130' align='center'>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/send_xml.php?pluginname=fusinvinventory&file=".$folder."/".$id."'>".$LANG['document'][15]."</a>";
         echo "</td>";
         echo "</tr>";

//         echo "<tr class='tab_bg_1'>";
//         echo "<td>";
//         echo "<pre width='130'>".$xml."</pre>";
//         echo "</td>";
//         echo "</tr>";
         echo "</table>";
      }
   }
}

?>
