<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryLibintegrity extends CommonDBTM {

   var $table = "glpi_plugin_fusinvinventory_libserialization";


   function showForm() {
      global $DB,$LANG;

      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();
      $Computer = new Computer();

      echo "<form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusinvinventory/front/libintegrity.form.php\">";
      echo "<table class='tab_cadre' width='500'>";
      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['buttons'][37];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";

      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
      $result=$DB->query($query);
		while ($a_computerlib=$DB->fetch_array($result)) {
         $computer_id = $a_computerlib['computers_id'];
         $a_sections = $PluginFusinvinventoryLib->_getInfoSections($a_computerlib['internal_id']);
         $Computer->getFromDB($computer_id);
         $text = "";
         foreach($a_sections['sections'] as $name=>$section) {
            //echo $name."<br/>";
            $split = explode("/", $name);
            if ($split[1] > 0) {
               switch ($split[0]) {

                  case 'CONTROLLERS':
                     $DeviceControl = new Computer_Device('DeviceControl');
                     if (!$DeviceControl->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][20]);
                     }
                     break;

                  case 'CPUS':
                     $DeviceProcessor = new Computer_Device('DeviceProcessor');
                     if (!$DeviceProcessor->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][4]);
                     }
                     break;

                  case 'DRIVES':
                     $ComputerDisk = new ComputerDisk();
                     if (!$ComputerDisk->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19]);
                     }
                     break;

                  case 'MEMORIES':
                     $DeviceMemory = new Computer_Device('DeviceMemory');
                     if (!$DeviceMemory->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][6]);
                     }
                     break;

                  case 'NETWORKS':
                     $NetworkPort = new NetworkPort();
                     if (!$NetworkPort->find("`id`='".$split[1]."' 
                                             AND `items_id`='".$computer_id."'
                                             AND `itemtype`='Computer'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['networking'][4]);
                     }
                     break;

                  case 'SOFTWARES':
                     $Computer_SoftwareVersion = new Computer_SoftwareVersion();
                     if (!$Computer_SoftwareVersion->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][31]);
                     }
                     break;

                  case 'SOUNDS':
                     $DeviceSoundCard = new Computer_Device('DeviceSoundCard');
                     if (!$DeviceSoundCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][7]);
                     }
                     break;

                  case 'STORAGES':
                     $array_section = unserialize($section);
                     $PluginFusinvinventoryImport_Storage = new PluginFusinvinventoryImport_Storage();
                     $type_tmp = $PluginFusinvinventoryImport_Storage->getTypeDrive($array_section);
                     if ($type_tmp == "Drive") {
                        $DeviceDrive = new Computer_Device('DeviceDrive');
                        if (!$DeviceDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19]);
                        }
                     } else {
                        $DeviceHardDrive = new Computer_Device('DeviceHardDrive');
                        if (!$DeviceHardDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][1]);
                        }
                     }
                     break;

                  case 'VIDEOS':
                     $DeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
                     if (!$DeviceGraphicCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][2]);
                     }
                     break;

                  case 'MONITORS':
                     $Computer_Item = new Computer_Item();
                     if (!$Computer_Item->find("`id`='".$split[1]."' 
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Monitor'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                     }
                     break;

                  case 'PRINTERS':
                     $Computer_Item = new Computer_Item();
                     if (!$Computer_Item->find("`id`='".$split[1]."'
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Printer'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][27]);
                     }
                     break;

                  case 'USBDEVICES':
                     $Computer_Item = new Computer_Item();
                     if (!$Computer_Item->find("`id`='".$split[1]."'
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Peripheral'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][29]);
                     }
                     break;

                  case 'BIOS':
                  case 'HARDWARE':
                     break;

                  default:
                     echo $name."<br/>";
                     break;

               }
            }
            if ($split[1] < 0) {
               switch ($split[0]) {

                  case 'MONITORS':
                     // Monitors must be created  but not created (in case
                     // of changes configuration of monitor import)
                     $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
                     if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_monitor") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_monitor = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_monitors` on `glpi_monitors`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Monitor'";
                           if ($result_monitor = $DB->query($query_monitor)) {
                              if ($DB->numrows($result_monitor) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                              }
                           }
                        }
                     } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_monitor") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_monitor = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_monitors` on `glpi_monitors`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Monitor'";
                        if ($result_monitor = $DB->query($query_monitor)) {
                           if ($DB->numrows($result_monitor) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                           }
                        }
                     }
                     break;

                  case 'PRINTERS':
                     // Printers must be created  but not created (in case
                     // of changes configuration of printer import)
                     $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
                     if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_printer") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_printer = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Printer'";
                           if ($result_printer = $DB->query($query_printer)) {
                              if ($DB->numrows($result_printer) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                              }
                           }
                        }
                     } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_printer") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_printer = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Printer'";
                        if ($result_printer = $DB->query($query_printer)) {
                           if ($DB->numrows($result_printer) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                           }
                        }
                     }
                     break;

               }
            }
         }
         if ($text != '') {

            echo "<tr>";
            echo "<th colspan='2'>";
            echo $Computer->getLink(1);
            echo "</th>";
            echo "</tr>";
            
            echo $text;
         }
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='2'>";
      echo "<input class='submit' type='submit' name='actionimport'
                      value='" . $LANG['buttons'][37] . "'>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      echo "</form>";
   }


   function displaySectionNotValid($computers_id, $sectionname, $name) {
      $text = "<tr class='tab_bg_1'>";
      $text .= "<td>";
      $text .= $name;
      $text .= "</td>";
      $text .= "<td align='center' width='30' >";
      $text .= "<input type=\"checkbox\" name=\"reimport[".$computers_id."/".$sectionname."]\" value=\"1\" >";
      $text .= "</td>";
      return $text;
   }


   function Import($import) {
      global $DB;

      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();

      $split = explode("/", $import);
      $computers_id = $split[0];
      $sectionname = $split[1];
      $sectioncomplete = $split[1]."/".$split[2];
      
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
         WHERE `computers_id`='".$computers_id."'
         LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      $a_sections = $PluginFusinvinventoryLib->_getInfoSections($data['internal_id']);

      $a_sectionadd = array();
      $a_sectionadd[0]['sectionName'] = $sectionname;
      $a_sectionadd[0]['dataSection'] = $a_sections['sections'][$sectioncomplete];
      $a_sectionid = PluginFusinvinventoryLibhook::addSections($a_sectionadd, $computers_id);
      $newsectioncomplete = $a_sectionid[0];

      $serializedSections = "";
      foreach($a_sections['sections'] as $name=>$datas) {
         if ($name == $sectioncomplete) {
            $name = $newsectioncomplete;
         }
         $serializedSections .= $name."<<=>>".$datas."
";
      }
      $PluginFusinvinventoryLib->_serializeIntoDB($data['internal_id'], $serializedSections);
   }
   
}

?>