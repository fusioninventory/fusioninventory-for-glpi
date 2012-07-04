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

class PluginFusinvinventoryLibintegrity extends CommonDBTM {

   var $table = "glpi_plugin_fusinvinventory_libserialization";

   
   
  function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['common'][32];

      $tab[1]['table']         = "glpi_computers";
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();
      $tab[1]['massiveaction'] = false; // implicit key==1

      return $tab;
   }
   
   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $DB,$LANG;

      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', "w")) {
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `computers_id`='".$item->getID()."'
               LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) > 0) {
               return self::createTabEntry($LANG['plugin_fusinvinventory']['menu'][4]);
            }
         }
      }
      return '';
   }

   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfAntivirus = new self();
         $pfAntivirus->showForm($item->getID());
      }

      return true;
   }
   
   

   /**
    * Display fields to add or delete to have right integrity between
    * XML from agent and GLPI
    * 
    * @param type $computers_id Set id of computer to check integrity 
    * or keep to 0 for all computers
    * 
    */
   function showForm($computers_id = 0) {
      global $DB,$LANG,$CFG_GLPI;

      $pfLib = new PluginFusinvinventoryLib();
      $Computer = new Computer();

      echo "<table width='950' align='center'>";
      echo "<tr>";      
      echo "<td align='left'>";
      echo "<form method='post' action=''>";
      echo "<input type='hidden' name='clean' value='1'/>";
      echo "<input type='submit' class='submit' value='".$LANG['buttons'][53]."'/>";
      Html::closeForm();
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<br/>";
      
      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }
      $where = "";
      if ($computers_id == '0') {
         $_SESSION["glpisearchcount"]["PluginFusinvinventoryLibintegrity"] = 1;
         Search::manageGetValues("PluginFusinvinventoryLibintegrity");
         Search::showGenericSearch("PluginFusinvinventoryLibintegrity", $_GET);

         if ($_GET['contains'][0] != '') {
            if (isset($_GET['searchtype'][0]) AND $_GET['searchtype'][0] == 'contains') {
               $where = " WHERE `name` LIKE '%".$_GET['contains'][0]."%' ";
            }
            if (isset($_GET['searchtype'][0]) AND $_GET['searchtype'][0] == 'equals') {
               $where = " WHERE `id`='".$_GET['contains'][0]."' ";
            }
         }
      } else {
          $where = " WHERE `id`='".$computers_id."' ";
      }
      
      // Total Number of events
      $query = "SELECT count(*) FROM `glpi_plugin_fusinvinventory_libserialization`
          LEFT JOIN `glpi_computers` on `computers_id` = `glpi_computers`.`id`
          ".$where." ";
      $result = $DB->query($query);
      $t = $DB->fetch_row($result);
      $number = $t[0];
      
      // Display the pager
      if ($computers_id == '0') {
         Html::printPager($start,$number,$CFG_GLPI['root_doc']."/plugins/fusinvinventory/front/libintegrity.php",'');
      }
      echo "<form method='post' name='integritylist' id='integritylist'  action=\"".$CFG_GLPI['root_doc'] . "/plugins/fusinvinventory/front/libintegrity.php\">";
      echo "<table class='tab_cadre' width='950'>";
      
      echo "<tr>";
      echo "<th colspan='4'>";
      echo $LANG['plugin_fusinvinventory']['menu'][4];
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>";
      echo $LANG['common'][17];
      echo "</th>";
      echo "<th>";
      echo $LANG['common'][16];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusinvinventory']['integrity'][0];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusinvinventory']['integrity'][1];
      echo "</th>";
      echo "</tr>";

      $query = "SELECT `glpi_plugin_fusinvinventory_libserialization`.* FROM `glpi_plugin_fusinvinventory_libserialization`
          LEFT JOIN `glpi_computers` on `computers_id` = `glpi_computers`.`id`
          ".$where."
          LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);
      $result=$DB->query($query);
      while ($a_computerlib=$DB->fetch_array($result)) {
         $computer_id = $a_computerlib['computers_id'];
         $a_sections = $pfLib->_getInfoSections($a_computerlib['internal_id']);
         $Computer->getFromDB($computer_id);
         $text = "";
         $a_sections_lib = array();
         foreach($a_sections['sections'] as $name=>$section) {
            $split = explode("/", $name);
            if (($split[1] > 0) OR (strstr($split[1], 'd'))) {
               $a_sectiontmp = unserialize($section);
               switch ($split[0]) {

                  case 'CONTROLLERS':
                     $DeviceControl = new Computer_Device('DeviceControl');
                     if (!$a_lists = $DeviceControl->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][20], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['CONTROLLERS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'CPUS':
                     $DeviceProcessor = new Computer_Device('DeviceProcessor');
                     if (!$a_lists = $DeviceProcessor->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][4], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['CPUS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'DRIVES':
                     $ComputerDisk = new ComputerDisk();
                     if (!$a_lists = $ComputerDisk->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $name = '';
                        if ((isset($a_sectiontmp['LABEL'])) AND (!empty($a_sectiontmp['LABEL']))) {
                           $name=$a_sectiontmp['LABEL'];
                        } else if (((!isset($a_sectiontmp['VOLUMN'])) OR (empty($a_sectiontmp['VOLUMN']))) AND (isset($a_sectiontmp['LETTER']))) {
                           $name=$a_sectiontmp['LETTER'];
                        } else if (isset($a_sectiontmp['TYPE'])) {
                           $name=$a_sectiontmp['TYPE'];
                        } else if (isset($a_sectiontmp['VOLUMN'])) {
                           $name=$a_sectiontmp['VOLUMN'];
                        }
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19], $a_sectiontmp['TYPE']." (".$name.")");
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['DRIVES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'MEMORIES':
                     $DeviceMemory = new Computer_Device('DeviceMemory');
                     if (!$a_lists = $DeviceMemory->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][6], $a_sectiontmp['DESCRIPTION']);
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
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['networking'][4], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['NETWORKS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'SOFTWARES':
                     $Computer_SoftwareVersion = new Computer_SoftwareVersion();
                     if (!$a_lists = $Computer_SoftwareVersion->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][31], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['SOFTWARES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'SOUNDS':
                     $DeviceSoundCard = new Computer_Device('DeviceSoundCard');
                     if (!$a_lists = $DeviceSoundCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][7], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['SOUNDS'][$a_list['id']] = 1;
                     }
                     break;

                  case 'STORAGES':
                     $array_section = unserialize($section);
                     $pfImport_Storage = new PluginFusinvinventoryImport_Storage();
                     $type_tmp = $pfImport_Storage->getTypeDrive($array_section);
                     if ($type_tmp == "Drive") {
                        $DeviceDrive = new Computer_Device('DeviceDrive');
                        $split[1] = str_replace("d", "", $split[1]);
                        if (!$a_lists = $DeviceDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][19], $a_sectiontmp['NAME']);
                        } else {
                           $a_list = current($a_lists);
                           $a_sections_lib['Drive'][$a_list['id']] = 1;
                        }
                     } else {
                        $DeviceHardDrive = new Computer_Device('DeviceHardDrive');
                        if (!$a_lists = $DeviceHardDrive->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                           $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][1], $a_sectiontmp['NAME']);
                        } else {
                           $a_list = current($a_lists);
                           $a_sections_lib['STORAGES'][$a_list['id']] = 1;
                        }
                     }
                     break;

                  case 'VIDEOS':
                     $DeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
                     if (!$a_lists = $DeviceGraphicCard->find("`id`='".$split[1]."' AND `computers_id`='".$computer_id."'")) {
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['devices'][2], $a_sectiontmp['NAME']);
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
                        $descriptiontmp = '';
                        if (isset($a_sectiontmp['DESCRIPTION'])) {
                           $descriptiontmp = $a_sectiontmp['DESCRIPTION'];
                        }
                        $serialtmp = '';
                        if (isset($a_sectiontmp['SERIAL'])) {
                           $serialtmp = $a_sectiontmp['SERIAL'];
                        }
                        
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], 
                                $a_sectiontmp['CAPTION'].", ".$descriptiontmp." (".$serialtmp.")");
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
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][27], $a_sectiontmp['NAME']);
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
                        $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][29], $a_sectiontmp['NAME']);
                     } else {
                        $a_list = current($a_lists);
                        $a_sections_lib['USBDEVICES'][$a_list['id']] = 1;
                     }
                     break;

                  case 'BIOS':
                  case 'HARDWARE':
                  case 'ANTIVIRUS':
                  case 'USERS':
                  case 'VIRTUALMACHINES':
                     break;

                  default:
                     echo $name."<br/>";
                     break;

               }
            }
            if ($split[1] < 0) {
               $a_sectiontmp = unserialize($section);
               switch ($split[0]) {

                  case 'MONITORS':
                     // Monitors must be created  but not created (in case
                     // of changes configuration of monitor import)
                     $pfConfig = new PluginFusioninventoryConfig();
                     if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_monitor") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_monitor = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_monitors` on `glpi_monitors`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Monitor'";
                           $result_monitor = $DB->query($query_monitor);
                           if ($result_monitor) {
                              if ($DB->numrows($result_monitor) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                              }
                           }
                        }
                     } else if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_monitor") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_monitor = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_monitors` on `glpi_monitors`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Monitor'";
                        $result_monitor = $DB->query($query_monitor);
                        if ($result_monitor) {
                           if ($DB->numrows($result_monitor) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                           }
                        }
                     }
                     break;

                  case 'PRINTERS':
                     // Printers must be created  but not created (in case
                     // of changes configuration of printer import)
                     $pfConfig = new PluginFusioninventoryConfig();
                     if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_printer") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_printer = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Printer'";
                           $result_printer = $DB->query($query_printer);
                           if ($result_printer) {
                              if ($DB->numrows($result_printer) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                              }
                           }
                        }
                     } else if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_printer") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_printer = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_printers` on `glpi_printers`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Printer'";
                        $result_printer = $DB->query($query_printer);
                        if ($result_printer) {
                           if ($DB->numrows($result_printer) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                           }
                        }
                     }
                     break;

                  case 'USBDEVICES':
                     // Printers must be created  but not created (in case
                     // of changes configuration of printer import)
                     $pfConfig = new PluginFusioninventoryConfig();
                     if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_peripheral") == '3') { //Import on serial number

                        $unserializedsection = unserialize($section);
                        if (isset($unserializedsection['SERIAL'])
                                AND !empty($unserializedsection['SERIAL'])) {

                           // Search in DB if exist
                           $query_peripheral = "SELECT * FROM `glpi_computers_items`
                              LEFT JOIN `glpi_peripherals` on `glpi_peripherals`.`id`=`items_id`
                              WHERE `computers_id`='".$computer_id."'
                                 AND `itemtype`='Peripherals'";
                           $result_peripheral = $DB->query($query_peripheral);
                           if ($result_peripheral) {
                              if ($DB->numrows($result_peripheral) == 0) {
                                 $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                              }
                           }
                        }
                     } else if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
                             "import_periheral") == '2') { //Import on serial number

                        // Search in DB if exist
                        $query_peripheral = "SELECT * FROM `glpi_computers_items`
                           LEFT JOIN `glpi_peripherals` on `glpi_peripherals`.`id`=`items_id`
                           WHERE `computers_id`='".$computer_id."'
                              AND `itemtype`='Peripheral'";
                        $result_peripheral = $DB->query($query_peripheral);
                        if ($result_peripheral) {
                           if ($DB->numrows($result_peripheral) == 0) {
                              $text .= $this->displaySectionNotValid($computer_id, $name, $LANG['help'][28], $a_sectiontmp['NAME']);
                           }
                        }
                     }
                     break;

               }
            }
         }

         // Check now sections in GLPI ant not in lib
         $computerDeviceControl = new Computer_Device('DeviceControl');
         $deviceControl = new DeviceControl();
         $computerDeviceProcessor = new Computer_Device('DeviceProcessor');
         $deviceProcessor = new DeviceProcessor();
         $ComputerDisk = new ComputerDisk();
         $computerDeviceMemory = new Computer_Device('DeviceMemory');
         $deviceMemory = new DeviceMemory();
         $NetworkPort = new NetworkPort();         
         $Computer_SoftwareVersion = new Computer_SoftwareVersion();
         $SoftwareVersion = new SoftwareVersion();
         $Software = new Software();
         $computerDeviceSoundCard = new Computer_Device('DeviceSoundCard');
         $deviceSoundCard = new DeviceSoundCard();
         $computerDeviceDrive = new Computer_Device('DeviceDrive');
         $deviceDrive = new DeviceDrive();
         $computerDeviceHardDrive = new Computer_Device('DeviceHardDrive');
         $deviceHardDrive = new DeviceHardDrive();
         $computerDeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
         $deviceGraphicCard = new DeviceGraphicCard();
         $Computer_Item = new Computer_Item();
         $monitor = new Monitor();
         $printer = new Printer();
         $peripheral = new Peripheral();
         
         $a_itemtypes = array();
         $a_itemtypes[] = 'CONTROLLERS';
         $a_itemtypes[] = 'CPUS';
         $a_itemtypes[] = 'DRIVES';
         $a_itemtypes[] = 'MEMORIES';
         $a_itemtypes[] = 'NETWORKS';
         $a_itemtypes[] = 'SOFTWARES';
         $a_itemtypes[] = 'SOUNDS';
         $a_itemtypes[] = 'Drive';
         $a_itemtypes[] = 'STORAGES';
         $a_itemtypes[] = 'VIDEOS';
         $a_itemtypes[] = 'MONITORS';
         $a_itemtypes[] = 'PRINTERS';
         $a_itemtypes[] = 'USBDEVICES';
         $a_sectionsGLPI = array();
         $itemtype = '';
         
         foreach($a_itemtypes as $sectionName) {
            
            switch ($sectionName) {

               case "CONTROLLERS":
                  $a_sectionsGLPI = $computerDeviceControl->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][20];
                  $itemtype = "DeviceControl";
                  break;

               case 'CPUS':
                  $a_sectionsGLPI = $computerDeviceProcessor->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][4];
                  $itemtype = "DeviceProcessor";
                  break;

               case 'DRIVES':
                  $a_sectionsGLPI = $ComputerDisk->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][19];
                  $itemtype = "ComputerDisk";
                  break;

               case 'MEMORIES':
                  $a_sectionsGLPI = $computerDeviceMemory->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][6];
                  $itemtype = "DeviceMemory";
                  break;

               case 'NETWORKS':
                  $a_sectionsGLPI = $NetworkPort->find("`items_id`='".$computer_id."'
                                             AND `itemtype`='Computer'");
                  $name = $LANG['networking'][4];
                  $itemtype = "NetworkPort";
                  break;

               case 'SOFTWARES':
                  $a_sectionsGLPI = $Computer_SoftwareVersion->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['help'][31];
                  $itemtype = "Computer_SoftwareVersion";
                  break;

               case 'SOUNDS':
                  $a_sectionsGLPI = $computerDeviceSoundCard->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][7];
                  $itemtype = "DeviceSoundCard";
                  break;

               case 'Drive':
                  $a_sectionsGLPI = $computerDeviceDrive->find("`computers_id`='".$computer_id."'");
                  $name = $LANG['devices'][19];
                  $itemtype = "DeviceDrive";
                  break;

               case 'STORAGES':
                  $a_sectionsGLPI = $computerDeviceHardDrive->find("`computers_id`='".$computer_id."'");
                  $name= $LANG['devices'][1];
                  $itemtype = "DeviceHardDrive";
                  break;

               case 'VIDEOS':
                  $a_sectionsGLPI = $computerDeviceGraphicCard->find("`computers_id`='".$computer_id."'");
                  $name= $LANG['devices'][2];
                  $itemtype = "DeviceGraphicCard";
                  break;

               case 'MONITORS':
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Monitor'");
                  $name = $LANG['help'][28];
                  $itemtype = "Computer_Item";
                  break;

               case "PRINTERS":
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Printer'");
                  $name = $LANG['help'][27];
                  $itemtype = "Computer_Item";
                  break;

               case 'USBDEVICES':
                  $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$computer_id."'
                                                AND `itemtype`='Peripheral'");
                  $name = $LANG['help'][29];
                  $itemtype = "Computer_Item";
                  break;

            }

            foreach($a_sectionsGLPI as $section_id=>$datasection) {
               $rname = '';

               if (!isset($a_sections_lib[$sectionName][$section_id])) {
                  switch ($itemtype) {
                     
                     case 'DeviceControl':
                        $computerDeviceControl->getFromDB($section_id);
                        $deviceControl->getFromDB($computerDeviceControl->fields['devicecontrols_id']);
                        $rname = $deviceControl->getLink();
                        break;
                     
                     case 'DeviceProcessor':
                        $computerDeviceProcessor->getFromDB($section_id);
                        $deviceProcessor->getFromDB($computerDeviceProcessor->fields['deviceprocessors_id']);
                        $rname = $deviceControl->getLink();
                        break;
                     
                     case 'ComputerDisk':
                        $ComputerDisk->getFromDB($section_id);
                        $rname = $ComputerDisk->getLink();
                        break;
                     
                     case 'DeviceMemory':
                        $computerDeviceMemory->getFromDB($section_id);
                        $deviceMemory->getFromDB($computerDeviceMemory->fields['devicememories_id']);
                        $rname = $deviceMemory->getLink();
                        break;
                     
                     case 'NetworkPort':
                        $NetworkPort->getFromDB($section_id);
                        $rname = $NetworkPort->getLink();
                        break;
                      
                     case 'Computer_SoftwareVersion':
                        $Computer_SoftwareVersion->getFromDB($section_id);
                        $SoftwareVersion->getFromDB($Computer_SoftwareVersion->fields['softwareversions_id']);
                        $Software->getFromDB($SoftwareVersion->fields['softwares_id']);
                        $rname = $Software->getLink()." ".$SoftwareVersion->getName();
                        break;
                     
                     case 'DeviceSoundCard':
                        $computerDeviceSoundCard->getFromDB($section_id);
                        $deviceSoundCard->getFromDB($computerDeviceSoundCard->fields['devicesoundcards_id']);
                        $rname = $deviceSoundCard->getLink();
                        break;
                     
                     case 'DeviceDrive':
                        $computerDeviceDrive->getFromDB($section_id);
                        $deviceDrive->getFromDB($computerDeviceDrive->fields['devicedrives_id']);
                        $rname = $deviceDrive->getLink();
                        break;
                     
                     case 'DeviceHardDrive':
                        $computerDeviceHardDrive->getFromDB($section_id);
                        $deviceHardDrive->getFromDB($computerDeviceHardDrive->fields['deviceharddrives_id']);
                        $rname = $deviceHardDrive->getLink();
                        break;
                     
                     case 'DeviceGraphicCard':
                        $computerDeviceGraphicCard->getFromDB($section_id);
                        $deviceGraphicCard->getFromDB($computerDeviceGraphicCard->fields['devicegraphiccards_id']);
                        $rname = $deviceGraphicCard->getLink();
                        break;
                     
                     case 'Computer_Item':
                        $Computer_Item->getFromDB($section_id);
                        if ($sectionName == 'MONITORS') {
                           $monitor->getFromDB($Computer_Item->fields['items_id']);
                           $rname = $monitor->getLink();
                        }
                        if ($sectionName == 'PRINTERS') {
                           $printer->getFromDB($Computer_Item->fields['items_id']);
                           $rname = $printer->getLink();
                        }
                        if ($sectionName == 'USBDEVICES') {
                           $peripheral->getFromDB($Computer_Item->fields['items_id']);
                           $rname = $peripheral->getLink();
                        }
                        break;
                      
                  }                   
                  $text .= $this->displaySectionNotValid($computer_id, $itemtype, $name, $rname, $section_id);
               }
            }
         }
         echo "<tr>";
         echo "<th colspan='4'>";
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
      echo "<th colspan='4' align='center'>";
         echo "<table align='center'>";
         echo "<tr>";
         echo "<td align='center'><a onclick= \"if ( markCheckboxes('integritylist') ) return false;\"
                   href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a></td>";
         echo "<td>/</td><td align='center'><a onclick= \"if ( unMarkCheckboxes('integritylist') )
               return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".
               $LANG["buttons"][19]."</a>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th colspan='4'>";
      echo "<input class='submit' type='submit' name='actionimport'
                      value='" . $LANG['buttons'][7] . "'>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();

      if ($computers_id == '0') {
         Html::printPager($start,$number,$CFG_GLPI['root_doc']."/plugins/fusinvinventory/front/libintegrity.php",'');
      }
   }


   
   /**
    * Set text to display when section is not valid between last inventory
    * and GLPI DB
    * 
    * @param type $computers_id
    * @param type $sectionname
    * @param type $name
    * @param type $value
    * @param type $onlyGLPI
    * 
    * @return string (text to display)
    */
   function displaySectionNotValid($computers_id, $sectionname, $name, $value='',$onlyGLPI = 0) {
      $text = "<tr class='tab_bg_1'>";
      $text .= "<td>";
      $text .= $name;
      if ($onlyGLPI != '0') {
         $text .= " (".$onlyGLPI.")";
      }
      $text .= "</td>";
      $text .= "<td>";
      $text .= $value;
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

   

   /**
    * Import sections of XML of agent because not in GLPI 
    * 
    * @param type $import list of sections to import into GLPI
    * 
    */
   function Import($import) {
      global $DB;

      $pfLib = new PluginFusinvinventoryLib();

      $split = explode("/", $import);
      $computers_id = $split[0];
      $sectionname = $split[1];
      $sectioncomplete = $split[1]."/".$split[2];
      
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`
         WHERE `computers_id`='".$computers_id."'
         LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      $a_sections = $pfLib->_getInfoSections($data['internal_id']);

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
         if (!strstr($name, "ENVS/")
                 AND !strstr($name, "PROCESSES/")) {

            $serializedSections .= $name."<<=>>".$datas."
";
                 }
      }
      $pfLib->_serializeIntoDB($data['internal_id'], $serializedSections);
   }



   /**
    * Delete fields in GLPI because not present in XML of agent
    * 
    * @param type $import list of fields/items to delete in GLPI
    * 
    */
   function deleteGLPI($import) {

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
   
   
   
   /**
    * This fonction is used to clean data in GLPI when not in last XML/libserialization
    */
   function cleanGLPI() {
      global $DB;
      
      ini_set("max_execution_time", "0");
      ini_set("memory_limit", "-1");
      
      $computer = new Computer();
      $pfLib    = new PluginFusinvinventoryLib();
      
      $computerDeviceControl     = new Computer_Device('DeviceControl');
      $computerDeviceProcessor   = new Computer_Device('DeviceProcessor');
      $ComputerDisk              = new ComputerDisk();
      $computerDeviceMemory      = new Computer_Device('DeviceMemory');
      $NetworkPort               = new NetworkPort();         
      $Computer_SoftwareVersion  = new Computer_SoftwareVersion();
      $computerDeviceSoundCard   = new Computer_Device('DeviceSoundCard');
      $computerDeviceHardDrive   = new Computer_Device('DeviceHardDrive');
      $computerDeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
      $Computer_Item             = new Computer_Item();
      
      $query = "SELECT * 
            FROM `glpi_plugin_fusinvinventory_libserialization`";
      $result=$DB->query($query);
      while ($a_computerlib=$DB->fetch_array($result)) {
         if (!$computer->getFromDB($a_computerlib['computers_id'])) {
            $query = "DELETE FROM `glpi_plugin_fusinvinventory_libserialization`
               WHERE `internal_id`='".$a_computerlib['internal_id']."'";
            $DB->query($query);
         } else {
            $a_sections = $pfLib->_getInfoSections($a_computerlib['internal_id']);
            $clean_sections = array();
            foreach ($a_sections['sections'] as $infos=>$datatmp) {
               $a_split = explode("/", $infos);
               if ($a_split[1] > 0) {
                  $clean_sections[$a_split[0]][$a_split[1]] = $a_split[1];
               }               
            }
            // Check now sections in GLPI ant not in lib
           
            $a_itemtypes = array();
            $a_itemtypes[] = 'CONTROLLERS';
            $a_itemtypes[] = 'CPUS';
            $a_itemtypes[] = 'DRIVES';
            $a_itemtypes[] = 'MEMORIES';
            $a_itemtypes[] = 'NETWORKS';
            $a_itemtypes[] = 'SOFTWARES';
            $a_itemtypes[] = 'SOUNDS';
            $a_itemtypes[] = 'Drive';
            $a_itemtypes[] = 'STORAGES';
            $a_itemtypes[] = 'VIDEOS';
            $a_itemtypes[] = 'MONITORS';
            $a_itemtypes[] = 'PRINTERS';
            $a_itemtypes[] = 'USBDEVICES';
            $a_sectionsGLPI = array();

            foreach($a_itemtypes as $sectionName) {

               switch ($sectionName) {

                  case "CONTROLLERS":
                     $a_sectionsGLPI = $computerDeviceControl->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['CONTROLLERS'][$items_id])) {
                           $computerDeviceControl->delete(array('id'=>$items_id,
                                                                "_itemtype" => 'DeviceControl'), 1);
                        }
                     }
                     break;

                  case 'CPUS':
                     $a_sectionsGLPI = $computerDeviceProcessor->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['CPUS'][$items_id])) {
                           $computerDeviceProcessor->delete(array('id'=>$items_id,
                                                                "_itemtype" => 'DeviceProcessor'), 1);
                        }
                     }
                     break;

                  case 'DRIVES':
                     $a_sectionsGLPI = $ComputerDisk->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['DRIVES'][$items_id])) {
                           $ComputerDisk->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

                  case 'MEMORIES':
                     $a_sectionsGLPI = $computerDeviceMemory->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['MEMORIES'][$items_id])) {
                           $computerDeviceMemory->delete(array('id'=>$items_id,
                                                               "_itemtype" => 'DeviceMemory'), 1);
                        }
                     }
                     break;

                  case 'NETWORKS':
                     $a_sectionsGLPI = $NetworkPort->find("`items_id`='".$a_computerlib['computers_id']."'
                                                AND `itemtype`='Computer'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['NETWORKS'][$items_id])) {
                           $NetworkPort->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

                  case 'SOFTWARES':
                     $a_sectionsGLPI = $Computer_SoftwareVersion->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['SOFTWARES'][$items_id])) {
                           $Computer_SoftwareVersion->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

                  case 'SOUNDS':
                     $a_sectionsGLPI = $computerDeviceSoundCard->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['SOUNDS'][$items_id])) {
                           $computerDeviceSoundCard->delete(array('id'=>$items_id,
                                                                  "_itemtype" => 'DeviceSoundCard'), 1);
                        }
                     }
                     break;

                  case 'STORAGES':
                     $a_sectionsGLPI = $computerDeviceHardDrive->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['STORAGES'][$items_id])) {
                           $computerDeviceHardDrive->delete(array('id'=>$items_id,
                                                                  "_itemtype" => 'DeviceHardDrive'), 1);
                        }
                     }
                     break;

                  case 'VIDEOS':
                     $a_sectionsGLPI = $computerDeviceGraphicCard->find("`computers_id`='".$a_computerlib['computers_id']."'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['VIDEOS'][$items_id])) {
                           $computerDeviceGraphicCard->delete(array('id'=>$items_id,
                                                                    "_itemtype" => 'DeviceGraphicCard'), 1);
                        }
                     }
                     break;

                  case 'MONITORS':
                     $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$a_computerlib['computers_id']."'
                                                   AND `itemtype`='Monitor'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['MONITORS'][$items_id])) {
                           $Computer_Item->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

                  case "PRINTERS":
                     $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$a_computerlib['computers_id']."'
                                                   AND `itemtype`='Printer'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['PRINTERS'][$items_id])) {
                           $Computer_Item->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

                  case 'USBDEVICES':
                     $a_sectionsGLPI = $Computer_Item->find("`computers_id`='".$a_computerlib['computers_id']."'
                                                   AND `itemtype`='Peripheral'");
                     foreach ($a_sectionsGLPI as $items_id=>$dataC) {
                        if (!isset($clean_sections['USBDEVICES'][$items_id])) {
                           $Computer_Item->delete(array('id'=>$items_id), 1);
                        }
                     }
                     break;

               }
            }
         }         
      }
   }
}

?>