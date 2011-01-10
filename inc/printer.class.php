<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
class PluginFusinvsnmpPrinter extends PluginFusinvsnmpCommonDBTM {
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
      $this->oFusionInventory_printer = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printers");
      $this->oFusionInventory_printer_history =
                        new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_printerlogs");
      $this->oFusionInventory_printer->type = 'PluginFusinvsnmpPrinter';
   }


   static function getTypeName() {
      global $LANG;

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

      $ptc = new PluginFusinvsnmpPrinterCartridge('glpi_plugin_fusinvsnmp_printercartridges');
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
   
	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		PluginFusioninventoryProfile::checkRight("fusinvsnmp", "printer","r");

		$plugin_fusioninventory_printer = new PluginFusinvsnmpPrinter;
		$plugin_fusioninventory_snmp = new PluginFusinvsnmpSNMP;

      $this->oFusionInventory_printer->id = $id;
      
      if (!$data = $this->oFusionInventory_printer->find("`printers_id`='".$id."'", '', 1)) {
         // Add in database if not exist
         $input = array();
         $input['printers_id'] = $id;
         $ID_tn = $this->oFusionInventory_printer->add($input);
         $this->oFusionInventory_printer->getFromDB($ID_tn);
      } else {
         foreach ($data as $ID_tn=>$datas) {
            $this->oFusionInventory_printer->fields = $data[$ID_tn];
         }
      }
      
		// Form printer informations

		echo "<div align='center'>";
      echo "<form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$options['target']."\">";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      
		echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][4];
      echo "</td>";
      echo "<td>";
      echo "<textarea name='sysdescr' cols='45' rows='5'>";
      echo $this->oFusionInventory_printer->fields['sysdescr'];
      echo "</textarea>";
      echo "</td>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusinvsnmp']['snmp'][53]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo convDateTime($this->oFusionInventory_printer->fields['last_fusioninventory_update']);
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' rowspan='2'>".$LANG['plugin_fusinvsnmp']['model_info'][4]."&nbsp;:</td>";
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
                     array('name'=>"model_infos",
                           'value'=>$this->oFusionInventory_printer->fields['plugin_fusinvsnmp_models_id'],
                           'comment'=>false,
                           'used'=>$exclude_models));
      echo "</td>";
      echo "<td colspan='2'>";

      echo "</td>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<input type='submit' name='GetRightModel'
              value='".$LANG['plugin_fusinvsnmp']['model_info'][13]."' class='submit'/>";
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusinvsnmp']['functionalities'][43]."&nbsp;:</td>";
		echo "<td align='center'>";
      PluginFusinvsnmpSNMP::auth_dropdown($this->oFusionInventory_printer->fields["plugin_fusinvsnmp_configsecurities_id"]);
		echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_2 center'>";
		echo "<td colspan='4'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='id' value='".$id."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

//      $this->oFusionInventory_printer->showFormButtons($options);
		echo "</table></form>";
		echo "</div>";

	}



}

?>