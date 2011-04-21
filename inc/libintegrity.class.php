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

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      // Total Number of events
      $number = countElementsInTable("glpi_plugin_fusinvinventory_libserialization",
                                     "");

      // Display the pager
      printPager($start,$number,GLPI_ROOT."/plugins/fusinvinventory/front/libintegrity.form.php",'');

      echo "<form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusinvinventory/front/libintegrity.form.php\">";
      echo "<table class='tab_cadre' width='950'>";
      
      echo "<tr>";
      echo "<th colspan='3'>";
      echo "Check integrity";
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>";
      echo $LANG['common'][16];
      echo "</th>";
      echo "<th>";
      echo "Only in GLPI (to delete)";
      echo "</th>";
      echo "<th>";
      echo "Only in last inventory (to import)";
      echo "</th>";
      echo "</tr>";

      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
          LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);
      $result=$DB->query($query);
		while ($a_computerlib=$DB->fetch_array($result)) {
         $computer_id = $a_computerlib['computers_id'];
         $a_sections = $PluginFusinvinventoryLib->_getInfoSections($a_computerlib['internal_id']);
         $Computer->getFromDB($computer_id);
         $text = "";
         $a_sections_lib = array();
         foreach($a_sections['sections'] as $name=>$section) {
            //echo $name."<br/>";
            $split = explode("/", $name);
            if ($split[1] > 0) {
               switch ($split[0]) {

                  case 'CONTROLLERS':
                     $DeviceControl = new Computer_Device('DeviceControl');
                     if (!$a_lists = $DeviceControl->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][20]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['CONTROLLERS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'CPUS':
                     $DeviceProcessor = new Computer_Device('DeviceProcessor');
                     if (!$a_lists = $DeviceProcessor->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][4]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['CPUS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'DRIVES':
                     $ComputerDisk = new ComputerDisk();
                     if (!$a_lists = $ComputerDisk->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['DRIVES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'MEMORIES':
                     $DeviceMemory = new Computer_Device('DeviceMemory');
                     if (!$a_lists = $DeviceMemory->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][6]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['MEMORIES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'NETWORKS':
                     $NetworkPort = new NetworkPort();
                     if (!$a_lists = $NetworkPort->find("`id`='".$split[1]."'
                                             AND `items_id`='".$computer_id."'
                                             AND `itemtype`='Computer'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['networking'][4]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['NETWORKS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'SOFTWARES':
                     $Computer_SoftwareVersion = new Computer_SoftwareVersion();
                     if (!$a_lists = $Computer_SoftwareVersion->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][31]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['SOFTWARES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'SOUNDS':
                     $DeviceSoundCard = new Computer_Device('DeviceSoundCard');
                     if (!$a_lists = $DeviceSoundCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][7]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['SOUNDS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'STORAGES':
                     $array_section = unserialize($section);
                     $PluginFusinvinventoryImport_Storage = new PluginFusinvinventoryImport_Storage();
                     $type_tmp = $PluginFusinvinventoryImport_Storage->getTypeDrive($array_section);
                     if ($type_tmp == "Drive") {
                        $DeviceDrive = new Computer_Device('DeviceDrive');
                        if (!$a_lists = $DeviceDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19]);
                        } else {
                           $a_list = current($a_lists);
                           $a_sections_lib['Drive'][$a_list['id']] = 1;
                        }
                     } else {
                        $DeviceHardDrive = new Computer_Device('DeviceHardDrive');
                        if (!$a_lists = $DeviceHardDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][1]);
                        } else {
                           $a_list = current($a_lists);
                           $a_sections_lib['STORAGES'][$a_list['id']] = 1;
                        }
                     }
                     break;

                  case 'VIDEOS':
                     $DeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
                     if (!$a_lists = $DeviceGraphicCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][2]);
                     } else {
                           $a_list = current($a_lists);
                           $a_sections_lib['VIDEOS'][$a_list['id']] = 1;
                        }
                     break;

                  case 'MONITORS':
                     $Computer_Item = new Computer_Item();
                     if (!$a_lists = $Computer_Item->find("`id`='".$split[1]."'
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Monitor'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['MONITORS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'PRINTERS':
                     $Computer_Item = new Computer_Item();
                     if (!$a_lists = $Computer_Item->find("`id`='".$split[1]."'
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Printer'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][27]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['PRINTERS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'USBDEVICES':
                     $Computer_Item = new Computer_Item();
                     if (!$a_lists = $Computer_Item->find("`id`='".$split[1]."'
                                                AND `computers_id`='".$computer_id."'
                                                AND `itemtype`='Peripheral'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][29]);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['USBDEVICES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'BIOS':
                  case 'HARDWARE':
                  case 'ANTIVIRUS':
                  case 'USERS':
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

                  case 'USBDEVICES':
                     // Printers must be created  but not created (in case
                     // of changes configuration of printer import)
                     $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
                     if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_peripheral") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_peripheral = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_peripherals` on `glpi_peripherals`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Peripherals'";
                           if ($result_peripheral = $DB->query($query_peripheral)) {
                              if ($DB->numrows($result_peripheral) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                              }
                           }
                        }
                     } else if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_periheral") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_peripheral = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_peripherals` on `glpi_peripherals`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Peripheral'";
                        if ($result_peripheral = $DB->query($query_peripheral)) {
                           if ($DB->numrows($result_peripheral) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28]);
                           }
                        }
                     }
                     break;

               }
            }
         }

         // Check now sections in GLPI ant not in lib
         foreach($a_sections_lib as $sectionName=>$data) {
            switch ($sectionName) {

               case "CONTROLLERS":
                  $DeviceControl = new Computer_Device('DeviceControl');
                  $a_sectionsGLPI = $DeviceControl->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][20];
                  $itemtype = "DeviceControl";
                  break;

               case 'CPUS':
                  $DeviceProcessor = new Computer_Device('DeviceProcessor');
                  $a_sectionsGLPI = $DeviceProcessor->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][4];
                  $itemtype = "DeviceProcessor";
                  break;

               case 'DRIVES':
                  $ComputerDisk = new ComputerDisk();
                  $a_sectionsGLPI = $ComputerDisk->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][19];
                  $itemtype = "ComputerDisk";
                  break;

               case 'MEMORIES':
                  $DeviceMemory = new Computer_Device('DeviceMemory');
                  $a_sectionsGLPI = $DeviceMemory->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][6];
                  $itemtype = "DeviceMemory";
                  break;

               case 'NETWORKS':
                  $NetworkPort = new NetworkPort();
                  $a_sectionsGLPI = $NetworkPort->find("`items_id`='".$computer_id."'
                                             AND `itemtype`='Computer'");
                  $name = $LANG['networking'][4];
                  $itemtype = "NetworkPort";
                  break;

               case 'SOFTWARES':
                  $Computer_SoftwareVersion = new Computer_SoftwareVersion();
                  $a_sectionsGLPI = $Computer_SoftwareVersion->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['help'][31];
                  $itemtype = "NetworkPort";
                  break;

               case 'SOUNDS':
                  $DeviceSoundCard = new Computer_Device('DeviceSoundCard');
                  $a_sectionsGLPI = $DeviceSoundCard->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][7];
                  $itemtype = "DeviceSoundCard";
                  break;

               case 'Drive':
                  $DeviceDrive = new Computer_Device('DeviceDrive');
                  $a_sectionsGLPI = $DeviceDrive->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][19];
                  $itemtype = "DeviceDrive";
                  break;

               case 'STORAGES':
                  $DeviceHardDrive = new Computer_Device('DeviceHardDrive');
                  $a_sectionsGLPI = $DeviceHardDrive->find("`computers_id`='".$computer_id."'");
                  $name= $LANG['devices'][1];
                  $itemtype = "DeviceHardDrive";
                  break;

               case 'VIDEOS':
                  $DeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
                  $a_sectionsGLPI = $DeviceGraphicCard->find("`computers_id`='".$computer_id."'");
                  $name= $LANG['devices'][2];
                  $itemtype = "DeviceGraphicCard";
                  break;

               case 'MONITORS':
                  $Computer_Item = new Computer_Item();
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Monitor'");
                  $name = $LANG['help'][28];
                  $itemtype = "Computer_Item";
                  break;

               case "PRINTERS":
                  $Computer_Item = new Computer_Item();
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Printer'");
                  $name = $LANG['help'][27];
                  $itemtype = "Computer_Item";
                  break;

               case 'USBDEVICES':
                  $Computer_Item = new Computer_Item();
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Peripheral'");
                  $name = $LANG['help'][29];
                  $itemtype = "Computer_Item";
                  break;

            }
            foreach($a_sectionsGLPI as $section_id=>$datasection) {
                if (!isset($data[$section_id])) {
                  $text .= $this->displaySectionNotValid($computer_id, $itemtype, $name, $section_id);
               }
            }

            
         }


         echo "<tr>";
         echo "<th colspan='3'>";
         echo $Computer->getLink(1);
         echo "</th>";
         echo "</tr>";
         if ($text != '') {
            echo $text;
         }
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='3'>";
      echo "<input class='submit' type='submit' name='actionimport'
                      value='" . $LANG['buttons'][7] . "'>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      echo "</form>";

      printPager($start,$number,GLPI_ROOT."/plugins/fusinvinventory/front/libintegrity.form.php",'');

   }


   function displaySectionNotValid($computers_id, $sectionname, $name, $onlyGLPI = 0) {
      $text = "<tr class='tab_bg_1'>";
      $text .= "<td>";
      $text .= $name;
      if ($onlyGLPI != '0') {
         $text .= " (".$onlyGLPI.")";
      }
      $text .= "</td>";
      $text .= "<td align='center' width='250' >";
      if ($onlyGLPI != '0') {
         $text .= "<input type=\"checkbox\" name=\"glpidelete[".$onlyGLPI."/".$sectionname."]\" value=\"1\" >";
      }
      $text .= "</td>";
      $text .= "<td align='center' width='250' >";
      if ($onlyGLPI == '0') {
         $text .= "<input type=\"checkbox\" name=\"reimport[".$computers_id."/".$sectionname."]\" value=\"1\" >";
      }
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


   function deleteGLPI($import) {
      global $DB;

      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();

      $split = explode("/", $import);
      $items_id = $split[0];
      $itemtype = $split[1];
      if (strstr($itemtype, "Device")) {
         $class = new Computer_Device($itemtype);
         $class->delete(array('id'=>$items_id,
                                   "_itemtype" => $itemtype), 1);
      } else {
         $class = new $itemtype();
         $class->delete(array('id'=>$items_id), 1);
      }
      

   }
   
}

?>