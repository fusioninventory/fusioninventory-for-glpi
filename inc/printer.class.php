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
 * Class to use networking switches
 **/
class PluginFusioninventoryPrinter extends PluginFusioninventoryCommonDBTM {
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
      $this->type=PRINTER_TYPE;
      $this->oFusionInventory_printer = new PluginFusioninventoryCommonDBTM("glpi_plugin_fusioninventory_printers");
      $this->oFusionInventory_printer_history =
                        new PluginFusioninventoryCommonDBTM("glpi_plugin_fusioninventory_printers_history");
   }

   /**
    * Load an existing networking switch
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);
      $this->ports = $this->getPortsDB();
      $this->cartridges = $this->getCartridgesDB();

      $query = "SELECT `ID`
                FROM `glpi_plugin_fusioninventory_printers`
                WHERE `FK_printers` = '".$this->getValue('ID')."';";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $fusioninventory = $DB->fetch_assoc($result);
            $this->oFusionInventory_printer->load($fusioninventory['ID']);
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_printer;
         } else {
            $this->oFusionInventory_printer->load();
            $this->oFusionInventory_printer->setValue('FK_printers', $this->getValue('ID'));
            $this->ptcdLinkedObjects[]=$this->oFusionInventory_printer;
         }

         $query = "SELECT *
                   FROM `glpi_plugin_fusioninventory_printers_history`
                   WHERE `FK_printers` = '".$this->getValue('ID')."'
                         AND LEFT(`date`, 10)='".date("Y-m-d")."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $history = $DB->fetch_assoc($result);
               $this->oFusionInventory_printer_history->load($history['ID']);
            } else {
               $this->oFusionInventory_printer_history->load();
               $this->oFusionInventory_printer_history->setValue('FK_printers', $this->getValue('ID'));
               $this->oFusionInventory_printer_history->setValue('date', date("Y-m-d H:i:s"));
            }
         } 
      }
   }

   /**
    * Update an existing preloaded switch with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      global $DB;

      if (array_key_exists('model', $this->ptcdUpdates)) {
         $manufacturer = Dropdown::getDropdownName("glpi_dropdown_manufacturer",
                                         $this->getValue('FK_glpi_enterprise'));
         $this->ptcdUpdates['model'] = Dropdown::importExternal("PrinterModel",
                                                   $this->ptcdUpdates['model'], 0,
                                                   array('manufacturer'=>$manufacturer));
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
      if (is_null($this->oFusionInventory_printer_history->getValue('ID'))) {
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

      $ptp = new PluginFusioninventoryPort();
      $query = "SELECT `ID`
                FROM `glpi_networking_ports`
                WHERE `on_device` = '".$this->getValue('ID')."'
                      AND `device_type` = '".PRINTER_TYPE."';";
      $portsIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($port = $DB->fetch_assoc($result)) {
               $ptp->load($port['ID']);
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
            if ($oPort->getValue('ifmac')==$p_mac) {
               $portIndex = $index;
               break;
            }
         }
      }
      if ($portIndex == '' AND $p_ip != '') {
         foreach ($this->ports as $index => $oPort) {
            if ($oPort->getValue('ifaddr')==$p_ip) {
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
      $CFG_GLPI["deleted_tables"][]="glpi_networking_ports"; // TODO : to clean
      
      foreach ($this->ports as $index=>$ptp) {
         if (!in_array($index, $this->updatesPorts)) { // delete ports which don't exist any more
            $ptp->deleteDB();
         }
      }
      foreach ($this->newPorts as $ptp) {
         if ($ptp->getValue('ID')=='') {               // create existing ports
            $ptp->addDB($this->getValue('ID'));
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
      $CFG_GLPI["deleted_tables"][]="glpi_plugin_fusioninventory_printers_cartridges"; // TODO : to clean

      foreach ($this->cartridges as $index=>$ptc) {
         if (!in_array($index, $this->updatesCartridges)) { // delete cartridges which don't exist any more
            $ptc->deleteDB();
         }
      }
      foreach ($this->newCartridges as $ptc) {
         if ($ptc->getValue('ID')=='') {               // create existing cartridges
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

      $ptc = new PluginFusioninventoryCommonDBTM('glpi_plugin_fusioninventory_printers_cartridges');
      $query = "SELECT `ID`
                FROM `glpi_plugin_fusioninventory_printers_cartridges`
                WHERE `FK_printers` = '".$this->getValue('ID')."';";
      $cartridgesIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($cartridge = $DB->fetch_assoc($result)) {
               $ptc->load($cartridge['ID']);
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
}

?>