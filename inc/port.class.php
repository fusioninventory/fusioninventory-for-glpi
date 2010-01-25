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
// Purpose of file: modelisation of a networking switch port
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use networking ports
 **/
class PluginTrackerPort extends PluginTrackerCommonDBTM {
   private $oTracker_networking_ports; // link tracker table object
   private $tracker_networking_ports_ID; // ID in link tracker table
   private $portsToConnect=array(); // ID of known connected ports
   private $connectedPort=''; // ID of connected ports
   private $unknownDevicesToConnect=array(); // IP and/or MAC addresses of unknown connected ports
   private $portVlans=array(); // number and name for each vlan
   private $cdp=false; // true if CDP=1
   private $glpi_type=NETWORKING_TYPE; // NETWORKING_TYPE, PRINTER_TYPE...

	/**
	 * Constructor
	**/
   function __construct($p_type=NULL) {
//   function __construct() {
      parent::__construct("glpi_networking_ports");
      $this->oTracker_networking_ports =
              new PluginTrackerCommonDBTM("glpi_plugin_tracker_networking_ports");
      if ($p_type!=NULL) $this->glpi_type = $p_type;
   }

   /**
    * Load an optionnaly existing port
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);
      if (is_numeric($p_id)) { // port exists
         $query = "SELECT `ID`
                   FROM `glpi_plugin_tracker_networking_ports`
                   WHERE `FK_networking_ports` = '".$p_id."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $portTracker = $DB->fetch_assoc($result);
               $this->tracker_networking_ports_ID = $portTracker['ID'];
               $this->oTracker_networking_ports->load($this->tracker_networking_ports_ID);
               $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
            } else {
               $this->tracker_networking_ports_ID = NULL;
               $this->oTracker_networking_ports->load();
               $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
            }
         }
      } else {
         $this->tracker_networking_ports_ID = NULL;
         $this->oTracker_networking_ports->load();
         $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
      }
   }

   /**
    * Update an existing preloaded port with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      parent::updateDB(); // update core
      $this->oTracker_networking_ports->updateDB(); // update tracker
      $this->connect(); // update connections
      $this->assignVlans(); // update vlans
   }

   /**
    * Add a new port with the instance values
    *
    *@param $p_id Networking ID
    *@param $p_force=FALSE Force add even if no updates where done
    *@return nothing
    **/
   function addDB($p_id, $p_force=FALSE) {
      if (count($this->ptcdUpdates) OR $p_force) {
         // update core
         $this->ptcdUpdates['on_device']=$p_id;
         $this->ptcdUpdates['device_type']=$this->glpi_type;
//         $this->ptcdUpdates['device_type']=NETWORKING_TYPE;
         $portID=parent::add($this->ptcdUpdates);
         $this->load($portID);
         // update tracker
         if (count($this->oTracker_networking_ports->ptcdUpdates) OR $p_force) {
            $this->oTracker_networking_ports->ptcdUpdates['FK_networking_ports']=$this->getValue('ID');
            $this->oTracker_networking_ports->add($this->oTracker_networking_ports->ptcdUpdates);
         }
         $this->connect();       // update connections
         $this->assignVlans();   // update vlans
      }
   }

   /**
    * Delete a loaded port
    *
    *@param $p_id Port ID
    *@return nothing
    **/
   function deleteDB() {
      $this->cleanVlan('', $this->getValue('ID'));
      $this->disconnectDB($this->getValue('ID'));
      $this->oTracker_networking_ports->deleteDB(); // tracker
      parent::deleteDB(); // core
   }

   /**
    * Add connection
    *
    *@param $p_port Port id
    *@return nothing
    **/
   function addConnection($p_port) {
      $this->portsToConnect[]=$p_port;
   }

   /**
    * Add connection to unknown device
    *
    *@param $p_mac MAC address
    *@param $p_ip IP address
    *@return nothing
    **/
   function addUnknownConnection($p_mac, $p_ip) {
      $this->unknownDevicesToConnect[]=array('mac'=>$p_mac, 'ip'=>$p_ip);
   }

   /**
    * Manage connection to unknown device
    *
    *@param $p_mac MAC address
    *@param $p_ip IP address
    *@return nothing
    **/
   function PortUnknownConnection($p_mac, $p_ip) {
      $ptud = new PluginTrackerUnknownDevice;
      $unknown_infos["name"] = '';
      $newID=$ptud->add($unknown_infos);
      // Add networking_port
      $np=new Netport;
      $port_add["on_device"] = $newID;
      $port_add["device_type"] = PLUGIN_TRACKER_MAC_UNKNOWN;
      $port_add["ifaddr"] = $p_ip;
      $port_add['ifmac'] = $p_mac;
      $dport = $np->add($port_add);
      $ptsnmp=new PluginTrackerSNMP;
      $this->connectDB($dport);
   }

   /**
    * Connect this port to another one in DB
    *
    *@param $destination_port ID of destination port
    *@return nothing
    **/
	function connect() {
      if (count($this->portsToConnect)+count($this->unknownDevicesToConnect)==0) {
         // no connections --> don't delete existing connections :
         // the connected device may be powered off
      } else {
         if ($this->getCDP() 
             OR count($this->portsToConnect)+count($this->unknownDevicesToConnect)==1) {
            // only one connection
            if (count($this->portsToConnect)) { // this connection is not on an unknown device
               $this->connectedPort = $this->portsToConnect[0];
               $this->connectDB($this->connectedPort);
            }
         } else {
            $index = $this->getConnectionToSwitchIndex();
            if ($index != '') {
               $this->connectedPort = $this->portsToConnect[$index];
               $this->connectDB($this->connectedPort);
            }
         }
         // update connections to unknown devices
         if (!count($this->portsToConnect)) { // if no known connection
            if (count($this->unknownDevicesToConnect)==1) { // if only one unknown connection
               $uConnection = $this->unknownDevicesToConnect[0];
               $this->PortUnknownConnection($uConnection['mac'], $uConnection['ip']);
            }
         }
      }
   }

    /**
    * Connect this port to another one in DB
    *
    *@param $destination_port ID of destination port
    *@return nothing
    **/
	function connectDB($destination_port='') {
		global $DB;

      $ptap = new PluginTrackerAgentsProcesses;

      $queryVerif = "SELECT *
                     FROM `glpi_networking_wire`
                     WHERE `end1` IN ('".$this->getValue('ID')."', '".$destination_port."')
                           AND `end2` IN ('".$this->getValue('ID')."', '".$destination_port."');";

      if ($resultVerif=$DB->query($queryVerif)) {
         if ($DB->numrows($resultVerif) == "0") { // no existing connection between those 2 ports
            $this->disconnectDB($this->getValue('ID')); // disconnect this port
            $this->disconnectDB($destination_port);     // disconnect destination port
            if (makeConnector($this->getValue('ID'),$destination_port)) { // connect those 2 ports
               $ptap->updateProcess($_SESSION['glpi_plugin_tracker_processnumber'],
                                    array('query_nb_connections_created' => '1'));
               plugin_tracker_addLogConnection("make",$this->getValue('ID'));
            }
         }
      }
   }

   /**
    * Disconnect a port in DB
    *
    *@param $p_port='' Port to disconnect
    *@return nothing
    **/
	function disconnectDB($p_port='') {
      if ($p_port=='') $p_port=$this;
      $netwire = new Netwire;
      plugin_tracker_addLogConnection("remove",$netwire->getOppositeContact($p_port));
      //plugin_tracker_addLogConnection("remove",$p_port);
      if (removeConnector($p_port)) {
         $ptap = new PluginTrackerAgentsProcesses;
         $ptap->updateProcess($_SESSION['glpi_plugin_tracker_processnumber'],
                              array('query_nb_connections_deleted' => '1'));
      }
   }

   /**
    * Add vlan
    *
    *@param $p_number Vlan number
    *@param $p_name Vlan name
    *@return nothing
    **/
   function addVlan($p_number, $p_name) {
      $this->portVlans[]=array('number'=>$p_number, 'name'=>$p_name);
   }

   /**
    * Assign vlans to this port
    *
    *@return nothing
    **/
   function assignVlans() {
      global $DB;
      
      if ($this->connectedPort=='') {
         // no connection to set check existing in DB
         $this->connectedPort=$this->getConnectedPortInDB($this->getValue('ID'));
      }
      $FK_vlans = array();
      foreach ($this->portVlans as $vlan) {
         $FK_vlans[] = externalImportDropdown("glpi_dropdown_vlan", $vlan['number'], 0, array(),
                                              $vlan['name']);
      }
      if (count($FK_vlans)) { // vlans to add/update
         $ports[] = $this->getValue('ID');
         if ($this->connectedPort != '') $ports[] = $this->connectedPort;
         foreach ($ports AS $num=>$tmp_port) {
            if ($num==1) { // connected port
               $ptpConnected = new PluginTrackerPort();
               $ptpConnected->load($tmp_port);
               if ($ptpConnected->fields['device_type']==NETWORKING_TYPE) {
                  break; // don't update if port on a switch
               }
            }
            $query = "SELECT *
                      FROM `glpi_networking_vlan`
                           LEFT JOIN `glpi_dropdown_vlan`
                              ON `glpi_networking_vlan`.`FK_vlan`=`glpi_dropdown_vlan`.`ID`
                      WHERE `FK_port`='$tmp_port'";
            if ($result=$DB->query($query)) {
               if ($DB->numrows($result) == "0") { // this port has no vlan
                  foreach ($FK_vlans as $FK_vlan) {
                     $this->assignVlan($tmp_port, $FK_vlan);
                  }
               } else { // this port has one or more vlans
                  $vlansDB = array();
                  $vlansDBnumber = array();
                  $vlansToAssign = array();
                  while ($vlanDB=$DB->fetch_assoc($result)) {
                     $vlansDBnumber[] = $vlanDB['name'];
                     $vlansDB[] = array('number'=>$vlanDB['name'], 'name'=>$vlanDB['comments'],
                                        'ID'=>$vlanDB['ID']);
                  }

                  foreach ($this->portVlans as $portVlan) {
                     $vlanInDB=false;
                     $key='';
                     foreach ($vlansDBnumber as $vlanKey=>$vlanDBnumber) {
                        if ($vlanDBnumber==$portVlan['number']) {
                           $key=$vlanKey;
                        }
                     }
                     if ($key !== '') {
                        unset($vlansDB[$key]);
                        unset($vlansDBnumber[$key]);
                     } else {
                        $vlansToAssign[] = $portVlan;
                     }
                  }
                  foreach ($vlansDB as $vlanToUnassign) {
                     $this->cleanVlan($vlanToUnassign['ID'], $tmp_port);
                  }
                  foreach ($vlansToAssign as $vlanToAssign) {
                     $FK_vlan = externalImportDropdown("glpi_dropdown_vlan", 
                                                       $vlanToAssign['number'], 0, array(),
                                                       $vlanToAssign['name']);
                     $this->assignVlan($tmp_port, $FK_vlan);
                  }
               }
            }
         }
      } else { // no vlan to add/update --> delete existing
         $query = "SELECT *
                   FROM `glpi_networking_vlan`
                   WHERE `FK_port`='".$this->getValue('ID')."'";
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) > 0) {// this port has one or more vlan
               $this->cleanVlan('', $this->getValue('ID'));
               if ($this->connectedPort != '') {
                  $ptpConnected = new PluginTrackerPort();
                  $ptpConnected->load($this->connectedPort);
                  if ($ptpConnected->fields['device_type'] != NETWORKING_TYPE) {
                     // don't update vlan on connected port if connected port on a switch
                     $this->cleanVlan('', $this->connectedPort);
                  }
               }
            }
         }
      }
   }

   /**
    * Assign vlan
    *
    *@param $p_port Port ID
    *@param $p_vlan Vlan ID
    *@return nothing
    **/
   function assignVlan($p_port, $p_vlan) {
      global $DB;

      $query = "INSERT INTO glpi_networking_vlan (FK_port,FK_vlan)
                VALUES ('$p_port','$p_vlan')";
      $DB->query($query);
   }

   /**
    * Clean vlan
    *
    *@param $p_vlan Vlan ID
    *@param $p_port='' Port ID
    *@return nothing
    **/
   function cleanVlan($p_vlan, $p_port='') {
		global $DB;

      if ($p_vlan != '') {
         if ($p_port != '') { // delete this vlan for this port
            $query="DELETE FROM `glpi_networking_vlan`
                    WHERE `FK_vlan`='$p_vlan'
                          AND `FK_port`='$p_port';";
         } else { // delete this vlan for all ports
            $query="DELETE FROM `glpi_networking_vlan`
                    WHERE `FK_vlan`='$p_vlan';";
            // do not remove vlan in glpi_dropdown_vlan : manual remove
         }
      } else { // delete all vlans for this port
         $query="DELETE FROM `glpi_networking_vlan`
                 WHERE `FK_port`='$p_port';";
      }
      $DB->query($query);
	}

   /**
    * Get index of connection to switch
    *
    *@return index of connection in $this->portsToConnect
    **/
   private function getConnectionToSwitchIndex() {
      global $DB;

      $macs='';
      $ptp = new PluginTrackerPort;
      foreach($this->portsToConnect as $index=>$portConnection) {
         if ($macs!='') $macs.=', ';
         $ptp->load($portConnection);
         $macs.="'".$ptp->getValue('ifmac')."'";
         $ifmac[$index]=$ptp->getValue('ifmac');
      }
      if ($macs!='') {
         $query = "SELECT `ifmac`
                   FROM `glpi_networking`
                   WHERE `ifmac` IN (".$macs.");";
         $result=$DB->query($query);
         if ($DB->numrows($result) == 1) {
            $switch = $DB->fetch_assoc($result);
            return array_search($switch['ifmac'], $ifmac);
         }
      }
      return '';
   }

   /**
    * Get connected port in DB
    *
    *@param $p_portID Port ID of first port
    *@return Port ID of connected port or '' if no connection
    **/
   function getConnectedPortInDB($p_portID) {
      global $DB;

      $query = "SELECT `end1` AS `ID`
                FROM `glpi_networking_wire`
                WHERE `end2`='".$p_portID."'
                UNION
                SELECT `end2` AS `ID`
                FROM `glpi_networking_wire`
                WHERE `end1`='".$p_portID."';";
      $result=$DB->query($query);
      if ($DB->numrows($result) == 1) {
         $port = $DB->fetch_assoc($result);
         return $port['ID'];
      }
      return '';
   }

   /**
    * Set CDP
    *
    *@return nothing
    **/
   function setCDP() {
      $this->cdp=true;
   }

   /**
    * Get CDP
    *
    *@return true/false
    **/
   function getCDP() {
      return $this->cdp;
   }
}
?>
