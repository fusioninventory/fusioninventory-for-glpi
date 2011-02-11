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

class PluginFusinvinventoryBlacklist extends CommonDBTM {

   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['menu'][2];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $PluginFusinvinventoryCriteria = new PluginFusinvinventoryCriteria();

      $ong = array();
      $i = 1;
      $fields = $PluginFusinvinventoryCriteria->find("");
      foreach($fields as $data) {
         $ong[$i] = $data['name'];
         $i++;
      }

      return $ong;
   }


   function showArray($id) {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusinvinventoryCriteria = new PluginFusinvinventoryCriteria();

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan='4'>";

      $PluginFusinvinventoryCriteria->getFromDB($id);
      echo $LANG['plugin_fusinvinventory']['blacklist'][0]." - ".$PluginFusinvinventoryCriteria->fields['name'];
      echo "</th>";
      echo "<tr>";


      $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");
      $i = 0;
      foreach ($a_blacklist as $datablacklist) {
         if ($i == 0) {
            echo "<tr class='tab_bg_1'>";
         }
         echo "<td colspan='2' align='center' width='50%'>".$datablacklist['value']."</td>";
         if ($i == "1") {
            echo "</tr>";
            $i = -1;
         }
         $i++;
      }
      if ($i == "1") {
         echo "<td colspan='2' align='center' width='50%'></td>";
         echo "</tr>";
      }

      echo "</table>";

      return true;
   }


   function addForm($id) {
      global $LANG;


      $this->getEmpty();
      $this->showFormHeader();

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>".$LANG['plugin_fusinvinventory']['blacklist'][1]."</td>";
      echo "<td colspan='2'><input type='text' name='value'/>";
      echo "<input type='hidden' name='plugin_fusioninventory_criterium_id' value='".$id."'/>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons();
      
   }



   function cleanBlacklist($p_xml) {
      $xml = simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);

      if (isset($xml->CONTENT->BIOS->SSN)) {
         $xml->CONTENT->BIOS->SSN = trim($xml->CONTENT->BIOS->SSN);
      }

      $PluginFusinvinventoryCriteria = new PluginFusinvinventoryCriteria();
      $fields = $PluginFusinvinventoryCriteria->find("");
      
      foreach($fields as $id=>$data) {

         switch($data['comment']) {

            case 'ssn':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->SSN)) AND ($xml->CONTENT->BIOS->SSN ==$blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->SSN = "";
                  }
               }
               break;

            case 'uuid':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->HARDWARE->UUID)) AND ($xml->CONTENT->HARDWARE->UUID == $blacklist_data['value'])) {
                     $xml->CONTENT->HARDWARE->UUID = "";
                  }
               }
               break;

            case 'macAddress':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");
               
               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->NETWORKS)) {
                     foreach($xml->CONTENT->NETWORKS as $network) {
                        if ((isset($network->MACADDR)) AND ($network->MACADDR == $blacklist_data['value'])) {
                           $network->MACADDR = "";
                        }
                     }
                  }
               }
               break;

           case 'winProdKey':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) AND ($xml->CONTENT->HARDWARE->WINPRODKEY == $blacklist_data['value'])) {
                     $xml->CONTENT->HARDWARE->WINPRODKEY = "";
                  }
               }
              break;

           case 'smodel':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->SMODEL)) AND ($xml->CONTENT->BIOS->SMODEL == $blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->SMODEL = "";
                  }
               }
              break;

           case 'storagesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->STORAGES)) {
                     foreach($xml->CONTENT->STORAGES as $storage) {
                        if ((isset($storage->SERIALNUMBER)) AND ($storage->SERIALNUMBER == $blacklist_data['value'])) {
                           $storage->SERIALNUMBER = "";
                        }
                     }
                  }
               }
              break;

           case 'drivesSerial':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if (isset($xml->CONTENT->DRIVES)) {
                     foreach($xml->CONTENT->DRIVES as $drive) {
                        if ((isset($drive->SERIAL)) AND ($drive->SERIAL == $blacklist_data['value'])) {
                           $drive->SERIAL = "";
                        }
                     }
                  }
               }
              break;

           case 'assetTag':
               $a_blacklist = $this->find("`plugin_fusioninventory_criterium_id`='".$id."'");

               foreach($a_blacklist as $blacklist_id=>$blacklist_data) {
                  if ((isset($xml->CONTENT->BIOS->ASSETTAG)) AND ($xml->CONTENT->BIOS->ASSETTAG == $blacklist_data['value'])) {
                     $xml->CONTENT->BIOS->ASSETTAG = "";
                  }
               }
              break;
            
         }
      }
      return $xml->asXML();
   }

}

?>