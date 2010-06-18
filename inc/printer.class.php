<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: modelisation of a printer
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use printers
 **/
class PluginFusinvsnmpPrinter extends PluginFusioninventoryCommonDBTM {
   private $oFusionInventory_printer;
   private $oFusionInventory_printer_history;
   private $ports=array(), $newPorts=array(), $updatesPorts=array();
   private $cartridges=array(), $newCartridges=array(), $updatesCartridges=array();

	/**
	 * Constructor
	**/
   function __construct() {
      parent::__construct("glpi_printers");
      $this->dohistory=true;
      $this->oFusionInventory_printer = new PluginFusioninventoryCommonDBTM("glpi_plugin_fusinvsnmp_printers");
      $this->oFusionInventory_printer_history =
                        new PluginFusioninventoryCommonDBTM("glpi_plugin_fusinvsnmp_printerlogs");
   }

   /**
    * Load an existing networking printer
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);
      $this->ports = $this->getPortsDB();
      $this->cartridges = $this->getCartridgesDB();

      $query = "SELECT `id`
                FROM `glpi_plugin_fusinvsnmp_printers`
                WHERE `printers_id` = '".$this->getValue('id')."';";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $fusioninventory = $DB->fetch_assoc($result);
            $this->oFusionInventory_printer->load($fusioninventory['id']);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_printer;
         } else {
            $this->oFusionInventory_printer->load();
            $this->oFusionInventory_printer->setValue('printers_id', $this->getValue('id'));
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_printer;
         }

         $query = "SELECT *
                   FROM `glpi_plugin_fusinvsnmp_printerlogs`
                   WHERE `printers_id` = '".$this->getValue('id')."'
                         AND LEFT(`date`, 10)='".date("Y-m-d")."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $history = $DB->fetch_assoc($result);
               $this->oFusionInventory_printer_history->load($history['id']);
            } else {
               $this->oFusionInventory_printer_history->load();
               $this->oFusionInventory_printer_history->setValue('printers_id', $this->getValue('id'));
               $this->oFusionInventory_printer_history->setValue('date', date("Y-m-d H:i:s"));
            }
         } 
      }
   }

   /**
    * Update an existing preloaded printer with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      global $DB;

      if (array_key_exists('model', $this->ptcdUpdates)) {
         $manufacturer = Dropdown::getDropdownName("glpi_manufacturers",
                                         $this->getValue('manufacturers_id'));
         $this->ptcdUpdates['model'] = Dropdown::importExternal("PrinterModel",
                                                   $this->ptcdUpdates['model'], 0,
                                                   array('manufacturer'=>$manufacturer));
      }
      if (array_key_exists('location', $this->ptcdUpdates)) {
         $entity = $this->getValue('entities_id');
         $this->ptcdUpdates['location'] = Dropdown::importExternal("Location",
                                                   $this->ptcdUpdates['location'],
                                                   $entity);
      }
      parent::updateDB();
      // update last_fusioninventory_update even if no other update
      $this->setValue('last_fusioninventory_update', date("Y-m-d H:i:s"));
      $this->oFusionInventory_printer->updateDB();
      // ports
      $this->savePorts();
      // cartridges
      $this->saveCartridges();
      // history
      if (is_null($this->oFusionInventory_printer_history->getValue('id'))) {
         // update only if counters not already set for today
         $this->oFusionInventory_printer_history->updateDB();
      }
   }

   /**
    * Get ports
    *
    *@return Array of ports instances
    **/
   private function getPortsDB() {
      global $DB;

      $ptp = new PluginFusinvsnmpNetworkPort();
      $query = "SELECT `id`
                FROM `glpi_networkports`
                WHERE `items_id` = '".$this->getValue('id')."'
                      AND `itemtype` = '".PRINTER_TYPE."';";
      $portsIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($port = $DB->fetch_assoc($result)) {
               $ptp->load($port['id']);
               $portsIds[] = clone $ptp;
            }
         }
      }
      return $portsIds;
   }

   /**
    * Get ports
    *
    *@return Array of ports id
    **/
   function getPorts() {
      return $this->ports;
   }

   /**
    * Get index of port object
    *
    *@param $p_mac MAC address
    *@param $p_ip='' IP address
    *@return Index of port object in ports array or '' if not found
    **/
   function getPortIndex($p_mac, $p_ip='') {
      $portIndex = '';
      foreach ($this->ports as $index => $oPort) {
         if (is_object($oPort)) { // should always be true
            if ($oPort->getValue('mac')==$p_mac) {
               $portIndex = $index;
               break;
            }
         }
      }
      if ($portIndex == '' AND $p_ip != '') {
         foreach ($this->ports as $index => $oPort) {
            if ($oPort->getValue('ip')==$p_ip) {
               $portIndex = $index;
               break;
            }
         }
      }
      return $portIndex;
   }

   /**
    * Get index of cartridge object
    *
    *@param $p_name Cartridge name
    *@return Index of cartridge object in cartridges array or '' if not found
    **/
   function getCartridgeIndex($p_name) {
      $cartridgeIndex = '';
      foreach ($this->cartridges as $index => $oCartridge) {
         if (is_object($oCartridge)) { // should always be true
            if ($oCartridge->getValue('object_name')==$p_name) {
               $cartridgeIndex = $index;
               break;
            }
         }
      }
      return $cartridgeIndex;
   }

   /**
    * Get port object
    *
    *@param $p_index Index of port object in $ports
    *@return Port object in ports array
    **/
   function getPort($p_index) {
      return $this->ports[$p_index];
   }

   /**
    * Save new ports
    *
    *@return nothing
    **/
   function savePorts() {
      $CFG_GLPI["deleted_tables"][]="glpi_networkports"; // TODO : to clean
      
      foreach ($this->ports as $index=>$ptp) {
         if (!in_array($index, $this->updatesPorts)) { // delete ports which don't exist any more
            $ptp->deleteDB();
         }
      }
      foreach ($this->newPorts as $ptp) {
         if ($ptp->getValue('id')=='') {               // create existing ports
            $ptp->addDB($this->getValue('id'));
         } else {                                      // update existing ports
            $ptp->updateDB();
         }
      }
   }

   /**
    * Get cartridge object
    *
    *@param $p_index Index of cartridge object in $cartridges
    *@return Cartridge object in cartridges array
    **/
   function getCartridge($p_index) {
      return $this->cartridges[$p_index];
   }

   /**
    * Save new cartridges
    *
    *@return nothing
    **/
   function saveCartridges() {
      $CFG_GLPI["deleted_tables"][]="glpi_plugin_fusinvsnmp_printercartridges"; // TODO : to clean

      foreach ($this->cartridges as $index=>$ptc) {
         if (!in_array($index, $this->updatesCartridges)) { // delete cartridges which don't exist any more
            $ptc->deleteDB();
         }
      }
      foreach ($this->newCartridges as $ptc) {
         if ($ptc->getValue('id')=='') {               // create existing cartridges
            $ptc->addCommon();
         } else {                                      // update existing cartridges
            $ptc->updateDB();
         }
      }
   }

   /**
    * Add new port
    *
    *@param $p_oPort port object
    *@param $p_portIndex='' index of port in $ports if already exists
    *@return nothing
    **/
   function addPort($p_oPort, $p_portIndex='') {
      $this->newPorts[]=$p_oPort;
      if (is_int($p_portIndex)) {
         $this->updatesPorts[]=$p_portIndex;
      }
   }

   /**
    * Get cartridges
    *
    *@return Array of cartridges
    **/
   private function getCartridgesDB() {
      global $DB;

      $ptc = new PluginFusinvsnmpPrinterCartridge();
      $query = "SELECT `id`
                FROM `glpi_plugin_fusinvsnmp_printercartridges`
                WHERE `printers_id` = '".$this->getValue('id')."';";
      $cartridgesIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($cartridge = $DB->fetch_assoc($result)) {
               $ptc->load($cartridge['id']);
               $cartridgesIds[] = clone $ptc;
            }
         }
      }
      return $cartridgesIds;
   }

   /**
    * Add new cartridge
    *
    *@param $p_oCartridge Cartridge object
    *@param $p_cartridgeIndex='' index of cartridge in $cartridges if already exists
    *@return nothing
    **/
   function addCartridge($p_oCartridge, $p_cartridgeIndex='') {
      $this->newCartridges[]=$p_oCartridge;
      if (is_int($p_cartridgeIndex)) {
         $this->updatesCartridges[]=$p_cartridgeIndex;
      }
   }

   /**
    * Add new page counter
    *
    *@param $p_name Counter name
    *@param $p_state Counter state
    *@return nothing
    **/
   function addPageCounter($p_name, $p_state) {
         $this->oFusionInventory_printer_history->setValue($p_name, $p_state,
                                                   $this->oFusionInventory_printer_history, 0);
   }
   
	function showFormPrinter($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG,$FUSIONINVENTORY_MAPPING;

		PluginFusioninventoryAuth::checkRight("snmp_printers","r");

		include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/snmp.mapping.constant.php");

		$this->id = $id;

		$plugin_fusioninventory_printer = new PluginFusinvsnmpPrinter;
		$plugin_fusioninventory_snmp = new PluginFusinvsnmpSNMP;

		$query = "SELECT *
                FROM `glpi_plugin_fusinvsnmp_printers`
                WHERE `printers_id`=".$id." ";

		$result = $DB->query($query);
		$data = $DB->fetch_assoc($result);

		// Add in database if not exist
		if ($DB->numrows($result) == "0") {
			$query_add = "INSERT INTO `glpi_plugin_fusinvsnmp_printers` (`printers_id`)
                              VALUES('".$id."') ";

			$DB->query($query_add);
		}

		// Form printer informations
      $this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["model_info"][4]."</td>";
		echo "<td align='center'>";
		$query_models = "SELECT *
                       FROM `glpi_plugin_fusinvsnmp_models`
                       WHERE `itemtype`!=3
                             AND `itemtype`!=0";
		$result_models=$DB->query($query_models);
		$exclude_models = array();
		while ($data_models=$DB->fetch_array($result_models)) {
			$exclude_models[] = $data_models['id'];
		}
		Dropdown::show("PluginFusinvsnmpModel",
                     array('name'=>"plugin_fusioninventory_snmpmodels_id",
                           'value'=>$data["plugin_fusioninventory_snmpmodels_id"],
                           'comment'=>false,
                           'used'=>$exclude_models));
      echo "</td>";
      echo "<td align='center'>";
      echo " <input type='submit' name='GetRightModel' value='".
             $LANG['plugin_fusioninventory']["model_info"][13]."' class='submit'/></td>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["functionalities"][43]."</td>";
		echo "<td align='center'>";
		PluginFusinvsnmpSNMP::auth_dropdown($data["plugin_fusioninventory_snmpauths_id"]);
		echo "</td>";
      echo "<td>";
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' colspan='2' height='30'>";
		echo $LANG['plugin_fusioninventory']["snmp"][52].": ".convDateTime($data["last_fusioninventory_update"]);
		echo "</td>";
      echo "<td>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='3'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='id' value='".$id."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";
		echo "</div>";

      // Remote action of agent
      $pfit = new PluginFusioninventoryTask;
      $pfit->RemoteStateAgent($target, $id, PRINTER_TYPE, array('INVENTORY' => 1 ));


		// ** FORM FOR CARTRIDGES

		// get infos to get visible or not the counters
			$snmp_model_ID = $plugin_fusioninventory_snmp->GetSNMPModel($id,PRINTER_TYPE);
			// ** Get link OID fields
			$Array_Object_TypeNameConstant= $plugin_fusioninventory_snmp->GetLinkOidToFields($id,PRINTER_TYPE);
			$mapping_name=array();
			foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name) {
				if ((strstr($mapping_type_name, "cartridge")) OR (strstr($mapping_type_name, "toner"))) {
               switch($mapping_type_name) {
                     CASE "cartridgeblack":
                        $mapping_name[$mapping_type_name] = "1";
                        break;

                     CASE "cartridgeblackphoto":
                        $mapping_name[$mapping_type_name] = "2";
                        break;

                     CASE "tonerblack" :
                        $mapping_name[$mapping_type_name] = "3";
                        break;

                     CASE "tonerblack2" :
                        $mapping_name[$mapping_type_name] = "4";
                        break;

                     CASE "cartridgecyan":
                        $mapping_name[$mapping_type_name] = "5";
                        break;

                     CASE "cartridgecyanlight":
                        $mapping_name[$mapping_type_name] = "6";
                        break;

                     CASE "tonercyan" :
                        $mapping_name[$mapping_type_name] = "7";
                        break;

                     CASE "cartridgemagenta":
                        $mapping_name[$mapping_type_name] = "8";
                        break;

                     CASE "cartridgemagentalight":
                        $mapping_name[$mapping_type_name] = "9";
                        break;

                     CASE "tonermagenta":
                        $mapping_name[$mapping_type_name] = "10";
                        break;

                     CASE "cartridgeyellow":
                        $mapping_name[$mapping_type_name] = "11";
                        break;

                     CASE "toneryellow":
                        $mapping_name[$mapping_type_name] = "12";
                        break;

                     CASE "drumblack":
                        $mapping_name[$mapping_type_name] = "13";
                        break;

                     CASE "drumcyan":
                        $mapping_name[$mapping_type_name] = "14";
                        break;

                     CASE "drummagenta":
                        $mapping_name[$mapping_type_name] = "15";
                        break;

                     CASE "drumyellow":
                        $mapping_name[$mapping_type_name] = "16";
                        break;

                     CASE "wastetoner":
                        $mapping_name[$mapping_type_name] = "17";
                        break;

                     CASE "maintenancekit":
                        $mapping_name[$mapping_type_name] = "18";
                        break;

                     default:
                        $mapping_name[$mapping_type_name] = "19";
               }
				}
			}

		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr class='tab_bg_1'>";
		echo "<th align='center' colspan='3'>";
		echo $LANG["cartridges"][16];
		echo "</th>";
		echo "</tr>";

		asort($mapping_name);
		foreach ($mapping_name as $cartridge_name=>$val) {
			$state = $plugin_fusioninventory_printer->cartridges_state($id, $cartridge_name);
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo $FUSIONINVENTORY_MAPPING[PRINTER_TYPE][$cartridge_name]['shortname'];
			echo " : ";
			echo "</td>";
			echo "<td align='center'>";
			echo "</td>";
			echo "<td align='center'>";
			PluginFusioninventoryDisplay::bar($state['state']);
			echo "</td>";
			echo "</tr>";
		}

		echo "</table></form>";
  		echo "</div>";
	}


   /**
    * Show printer graph form
    **/
   function showFormPrinter_graph($id, $options=array()) {
      global $FUSIONINVENTORY_MAPPING, $LANG, $DB;

      include_once(GLPI_ROOT."/plugins/fusioninventory/inc_constants/snmp.mapping.constant.php");

      $where=''; $begin=''; $end=''; $timeUnit='day'; $graphField='pages_total'; $printersComp = array();
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_begin'])) {
         $begin=$_SESSION['glpi_plugin_fusioninventory_graph_begin'];
      }
      if ( $begin == 'NULL' OR $begin == '' ) $begin=date("Y-m-01"); // first day of current month
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_end'])) {
         $end=$_SESSION['glpi_plugin_fusioninventory_graph_end'];
      }
      if ( $end == 'NULL' OR $end == '' ) $end=date("Y-m-d");; // today
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'])) $timeUnit=$_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'];
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_graphField'])) $graphField=$_SESSION['glpi_plugin_fusioninventory_graph_graphField'];
      if (!isset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) $_SESSION['glpi_plugin_fusioninventory_graph_printersComp']=array();
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'])) {
         $printerCompAdd=$_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'];
         if (!key_exists($printerCompAdd, $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
            $oPrinter = new Printer();
            if ($oPrinter->getFromDB($printerCompAdd)){
               $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$printerCompAdd] = $oPrinter->getField('name');
            }
         }
      } elseif (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove'])) {
         unset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove']]);
      }

      $printers = $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'];
      $printersView = $printers; // printers without the current printer
      if (isset($printersView[$p_ID])) {
         unset($printersView[$p_ID]);
      } else {
         $oPrinter = new Printer();
         if ($oPrinter->getFromDB($p_ID)){
            $printers[$p_ID] = $oPrinter->getField('name');
         }
      }

      $printersList = '';
      foreach ($printers as $printer) {
         if ($printersList != '') $printersList .= '<BR>';
         $printersList .= $printer;
      }
      $printersIds = "";
      foreach (array_keys($printers) as $printerId) {
         if ($printersIds != '') $printersIds.=', ';
         $printersIds .= $printerId;
      }

      $where = " WHERE `printers_id` IN(".$printersIds.")";
      if ($begin!='' || $end!='') {
            $where .= " AND " .$DB->getDateRequest("`date`",$begin,$end);
         }
      switch ($timeUnit) {
         case 'day':
            $group = "GROUP BY `printers_id`, `year`, `month`, `day`";
            break;
         case 'week':
            $group = "GROUP BY `printers_id`, `year`, `month`, `week`";
            break;
         case 'month':
            $group = "GROUP BY `printers_id`, `year`, `month`";
            break;
         case 'year':
            $group = "GROUP BY `printers_id`, `year`";
            break;
      }

      $query = "SELECT `printers_id`, DAY(`date`) AS `day`, WEEK(`date`) AS `week`,
                       MONTH(`date`) AS `month`, YEAR(`date`) AS `year`,
                       SUM(`$graphField`) AS `$graphField`
                FROM `glpi_plugin_fusinvsnmp_printerlogs`"
                .$where
                .$group."
                ORDER BY `year`, `month`, `day`, `printers_id`";

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][30]."&nbsp;:</td><td class='left' colspan='2'>";
      $elementsField=array('pages_total'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['shortname'],
                      'pages_n_b'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['shortname'],
                      'pages_color'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['shortname'],
                      'pages_recto_verso'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['shortname'],
                      'scanned'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['shortname'],
                      'pages_total_print'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['shortname'],
                      'pages_n_b_print'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['shortname'],
                      'pages_color_print'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['shortname'],
                      'pages_total_copy'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['shortname'],
                      'pages_n_b_copy'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['shortname'],
                      'pages_color_copy'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['shortname'],
                      'pages_total_fax'=>$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['shortname']);
      Dropdown::showFromArray('graph_graphField', $elementsField,
                              array('value'=>$graphField));
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['search'][8]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      showDateFormItem("graph_begin", $begin);
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['search'][9]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      showDateFormItem("graph_end", $end);
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][31]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      $elementsTime=array('day'=>$LANG['plugin_fusioninventory']["prt_history"][34],
                          'week'=>$LANG['plugin_fusioninventory']["prt_history"][35],
                          'month'=>$LANG['plugin_fusioninventory']["prt_history"][36],
                          'year'=>$LANG['plugin_fusioninventory']["prt_history"][37]);
      Dropdown::showFromArray('graph_timeUnit', $elementsTime,
                              array('value'=>$timeUnit));
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['Menu'][2]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      echo $printersList;
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_2'><td class='center' colspan='3'>
               <input type='submit' class=\"submit\" name='graph_plugin_fusioninventory_printer_period'
                      value='" . $LANG["buttons"][7] . "'>";
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][32]."&nbsp;:</td><td class='left'>";
      Computer_Item::dropdownConnect(PRINTER_TYPE,PRINTER_TYPE,"graph_printerCompAdd", -1, 0, array_keys($printers));
      echo "</td><td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][8]."\" class='submit' name='graph_plugin_fusioninventory_printer_add'>";
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][33]."&nbsp;:</td>
                                 <td class='left'>";
      $printersTmp = $printersView;
      $printersTmp[0] = "-----";
      asort($printersTmp);
      Dropdown::showFromArray('graph_printerCompRemove', $printersTmp);
      echo "</td><td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][6]."\" class='submit' name='graph_plugin_fusioninventory_printer_remove'>";
      echo "</td></tr>\n";
      echo "</table>";
      echo "</form>";

      echo "<div class=center>";
      $title = $elementsField[$graphField];
      if (count($printers)) {
         $ptg = new PluginFusinvsnmpGraph($query, $graphField, $timeUnit, $printers, $title);
      }
      echo '</div>';
   }
}

?>