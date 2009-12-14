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
// Purpose of file: modelisation of a networking switch
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use networking switches
 **/
class PluginTrackerNetworking2 extends CommonDBTM {
   private $ID, $name, $firmware, $serial, $ifaddr, $ifmac, $model, $comments,
           $ram, $memory, $uptime, $ports=array(), $cpu, $ifaddrs=array();
   private $oTracker_networking, $oTracker_networking_ifaddr, $oTracker_networking_ports;
   private $updates=array(), $newPorts=array(), $updatesPorts=array();
   private $newIfaddrs=array(), $updatesIfaddrs=array();

	/**
	 * Constructor
	**/
   function __construct() {
      $this->table="glpi_networking";
      $this->dohistory=true;
      $this->type=NETWORKING_TYPE;
      $this->oTracker_networking = new CommonDBTM;
      $this->oTracker_networking->table="glpi_plugin_tracker_networking";
   }

   /**
    * Load an existing switch
    *
    *@return nothing
    **/
   function load($p_id) {
      $this->ID = $p_id;

      $this->ifaddrs = $this->getIfaddrsDB();
      $this->ports = $this->getPortsDB();
      $this->oTracker_networking->getFromDB($p_id);

      $this->getFromDB($p_id);
      $this->ID = $this->fields['ID'];
      $this->name = $this->fields['name'];
      $this->firmware = $this->fields['firmware']; // via dropdown
      $this->serial = $this->fields['serial'];
      $this->ifaddr = $this->fields['ifaddr']; // et glpi_plugin_tracker_networking_ifaddr
      $this->ifmac = $this->fields['ifmac'];
      $this->model = $this->fields['model']; // via dropdown
      $this->comments = $this->fields['comments'];
      $this->ram = $this->fields['ram'];
      $this->memory = $this->oTracker_networking->fields['memory']; //tracker
      $this->uptime = $this->oTracker_networking->fields['uptime']; //tracker
      $this->cpu = $this->oTracker_networking->fields['cpu']; //tracker
   }

   /**
    * Update an existing preloaded switch with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      if (count($this->updates)) {
         // special for dropdowns : model, firmware
         if (array_key_exists('model', $this->updates)) {
            $manufacturer = getDropdownName("glpi_dropdown_manufacturer",$this->fields['FK_glpi_enterprise']);
            $this->updates['model'] = externalImportDropdown("glpi_dropdown_model_networking",$this->updates['model'], 0, array('manufacturer'=>$manufacturer));
         }
         if (array_key_exists('firmware', $this->updates)) {
            $this->updates['firmware'] = externalImportDropdown("glpi_dropdown_firmware",$this->updates['firmware']);
         }

         $this->updates['ID'] = $this->ID;
         $this->oTracker_networking->update($this->updates);
         $this->update($this->updates);
      }
   }

   /**
    * Get all object vars and values
    *
    *@return Array of all class vars => values
    **/
   function getVars() {
      return get_object_vars($this);
   }

   /**
    * Get ports
    *
    *@return Array of ports instances
    **/
   private function getPortsDB() {
      global $DB;

      $ptp = new PluginTrackerPort();
      $query = "SELECT `ID`
                FROM `glpi_networking_ports`
                WHERE (`on_device` = '$this->ID' AND `device_type` = '".NETWORKING_TYPE."');";
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
    * Set ports
    *
    *@param $p_ports Array of ports id
    *@return nothing
    **/
/*   function setPorts($p_ports) {
//      foreach ($p_ports as $newPort) {
//         if ($this->getPort($newPort->getValue('mac'), $newPort->getValue('ip'))) {
//
//         }
//      }
      if (!in_array($this->ports, $port)) { // don't update if values are the same
         eval("return \$this->$p_field=\$p_value;");
         $this->updates[$p_field] = $p_value;
      }

   }*/

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
         if (is_object($oPort)) { //todo pourquoi ne serait ce pas vrai ?
            if ($oPort->fields['ifmac']==$p_mac) {
               $portIndex = $index;
               break;
            }
         }
      }
      if ($portIndex == '' AND $p_ip != '') {
         foreach ($this->ports as $index => $oPort) {
            if ($oPort->fields['ifaddr']==$p_ip) {
               $portIndex = $index;
               break;
            }
         }
      }
      return $portIndex;
   }

   /**
    * Get index of ifaddr object
    *
    *@param $p_ip='' IP address
    *@return Index of ifaddr object in ifaddrs array or '' if not found
    **/
   function getIfaddrIndex($p_ip) {
      $ifaddrIndex = '';
      foreach ($this->ifaddrs as $index => $oIfaddr) {
         if (is_object($oIfaddr)) { //todo pourquoi ne serait ce pas vrai ?
            if ($oIfaddr->fields['ifaddr']==$p_ip) {
               $ifaddrIndex = $index;
               break;
            }
         }
      }
      return $ifaddrIndex;
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
      $CFG_GLPI["deleted_tables"][]="glpi_networking_ports"; // todo : à ranger !

      foreach ($this->ports as $index=>$ptp) {
         if (!in_array($index, $this->updatesPorts)) {
            $ptp->deleteDB();
         }
      }
      foreach ($this->newPorts as $ptp) {
         if ($ptp->getValue('ID')=='') {
            $ptp->addDB($this->getValue('ID'));
         } else {
            $ptp->updateDB();
         }
      }
   }

   /**
    * Save ifadddrs
    *
    *@return nothing
    **/
   function saveIfaddrs() {
      $CFG_GLPI["deleted_tables"][]="glpi_plugin_tracker_networking_ifaddr"; // todo : à ranger !

      foreach ($this->ifaddrs as $index=>$pti) {
         if (!in_array($index, $this->updatesIfaddrs)) {
            $pti->deleteDB();
         }
      }
      foreach ($this->newIfaddrs as $pti) {
         if ($pti->getValue('ID')=='') {
            $pti->addDB($this->getValue('ID'));
         } else {
            $pti->updateDB();
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
    * Get ips
    *
    *@return Array of ips instances
    **/
   private function getIfaddrsDB() {
      global $DB;

      $pti = new PluginTrackerIfaddr();
      $query = "SELECT `ID`
                FROM `glpi_plugin_tracker_networking_ifaddr`
                WHERE `FK_networking` = '$this->ID';";
      $ifaddrsIds = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($ifaddr = $DB->fetch_assoc($result)) {
               $pti->load($ifaddr['ID']);
               $ifaddrsIds[] = clone $pti;
            }
         }
      }
      return $ifaddrsIds;
   }

   /**
    * Get ifaddr object
    *
    *@param $p_index Index of ifaddr object in $ifaddrs
    *@return Ifaddr object in ifaddrs array
    **/
   function getIfaddr($p_index) {
      return $this->ifaddrs[$p_index];
   }

   /**
    * Get field value
    *
    *@param $p_field Field
    *@return Field value / nothing if unknown field
    **/
   function getValue($p_field) {
      if (eval("return isset(\$this->\$p_field);")) {
         return eval("return \$this->$p_field;");
      }
   }

   /**
    * Set field value
    *
    *@param $p_field Field
    *@param $p_value Value
    *@return true if value set / false if unknown field
    **/
   function setValue($p_field, $p_value) {
      if (property_exists($this, $p_field)) {
         if (!eval("return \$this->$p_field==\$p_value;")) { // don't update if values are the same
            eval("return \$this->$p_field=\$p_value;");
            $this->updates[$p_field] = $p_value;
         }
         return true;
      } else {
         return false;
      }
   }

   /**
    * Add IP
    *
    *@param $p_oIfaddr Ifaddr object
    *@param $p_ifaddrIndex='' index of ifaddr in $ifaddrs if already exists
    *@return nothing
    **/
   function addIfaddr($p_oIfaddr, $p_ifaddrIndex='') {
      if (count($this->newIfaddrs)==0) { // the first IP goes in glpi_networking.ifaddr
         $this->setValue('ifaddr', $p_oIfaddr->getValue('ifaddr'));
      }
      $this->newIfaddrs[]=$p_oIfaddr;
      if (is_int($p_ifaddrIndex)) {
         $this->updatesIfaddrs[]=$p_ifaddrIndex;
      }
   }

}
?>
