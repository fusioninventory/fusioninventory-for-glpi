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
// Purpose of file: modelisation of a networking switch port
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use networking ports
 **/
class PluginFusinvsnmpNetworkPort extends PluginFusinvsnmpCommonDBTM {
   private $oFusioninventory_networkport; // link fusioninventory table object
   private $fusinvsnmp_networkports_id; // id in link fusioninventory table
   private $portsToConnect=array(); // id of known connected ports
   private $connectedPort=''; // id of connected ports
   private $unknownDevicesToConnect=array(); // IP and/or MAC addresses of unknown connected ports
   private $portVlans=array(); // number and name for each vlan
   private $portMacs=array();  // MAC addresses
   private $portIps=array();   // IP addresses
   private $cdp=false; // true if CDP=1
   private $noTrunk=false; // true if call to setNoTrunk()
   private $glpi_type=NETWORKING_TYPE; // NETWORKING_TYPE, PRINTER_TYPE...

	/**
	 * Constructor
	**/
   function __construct($p_type=NULL, $p_logFile='') {
      global $CFG_GLPI;
      
      if ($p_logFile != '') {
         $logFile = $p_logFile;
      } else {
         $logFile = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/communication_port_'.
                              time().'_'.rand(1,1000);
      }
      parent::__construct("glpi_networkports", $logFile);
      $this->oFusioninventory_networkport =
              new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkports");
      if ($p_type!=NULL) $this->glpi_type = $p_type;
      $this->type='PluginFusinvsnmpNetworkPort';
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
         $query = "SELECT `id`
                   FROM `glpi_plugin_fusinvsnmp_networkports`
                   WHERE `networkports_id` = '".$p_id."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $portFusionInventory = $DB->fetch_assoc($result);
               $this->fusinvsnmp_networkports_id = $portFusionInventory['id'];
               $this->oFusioninventory_networkport->load($this->fusinvsnmp_networkports_id);
               $this->ptcdLinkedObjects[]=$this->oFusioninventory_networkport;
            } else {
               $this->fusinvsnmp_networkports_id = NULL;
               $this->oFusioninventory_networkport->load();
               $this->ptcdLinkedObjects[]=$this->oFusioninventory_networkport;
//               $this->fusinvsnmp_networkports_id = $this->addDBFusionInventory();
//               $this->oFusioninventory_networkport->load($this->fusinvsnmp_networkports_id);
//               $this->ptcdLinkedObjects[]=$this->oFusioninventory_networkport;
            }
         }
      } else {
         $this->fusinvsnmp_networkports_id = NULL;
         $this->oFusioninventory_networkport->load();
         $this->ptcdLinkedObjects[]=$this->oFusioninventory_networkport;
      }
   }

   /**
    * Update an existing preloaded port with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      parent::updateDB(); // update core
      if ($this->glpi_type == 'NetworkEquipment') {
         $this->oFusioninventory_networkport->updateDB(); // update fusioninventory
      }
      $this->connect(); // update connections
      $this->assignVlans(); // update vlans
   }

   /**
    * Add a new port with the instance values
    *
    *@param $p_id Networking id
    *@param $p_force=FALSE Force add even if no updates where done
    *@return nothing
    **/
   function addDB($p_id, $p_force=FALSE) {
      if (count($this->ptcdUpdates) OR $p_force) {
         // update core
         $this->ptcdUpdates['items_id']=$p_id;
         $this->ptcdUpdates['itemtype']=$this->glpi_type;
//         $this->ptcdUpdates['itemtype']=NETWORKING_TYPE;
         $portID=parent::add($this->ptcdUpdates);
         $this->load($portID);
         // update fusioninventory
         if ($this->glpi_type == 'NetworkEquipment') {
         if (count($this->oFusioninventory_networkport->ptcdUpdates) OR $p_force) {
//            $this->oFusioninventory_networkport->ptcdUpdates['networkports_id']=$this->getValue('id');
//            $this->oFusioninventory_networkport->add($this->oFusioninventory_networkport->ptcdUpdates);
            $this->fusinvsnmp_networkports_id = $this->addDBFusionInventory();
         }
         }
         $this->load($portID);
         $this->connect();       // update connections
         $this->assignVlans();   // update vlans
      }
   }

   /**
    * Add a new FusionInventory port with the instance values
    *
    *@return FusionInventory networking port id
    **/
   function addDBFusionInventory() {
      $this->oFusioninventory_networkport->ptcdUpdates['networkports_id']=$this->getValue('id');
      $fusinvsnmp_networkports_id = $this->oFusioninventory_networkport->add($this->oFusioninventory_networkport->ptcdUpdates);
      return $fusinvsnmp_networkports_id;
   }

   /**
    * Delete a loaded port
    *
    *@param $p_id Port id
    *@return nothing
    **/
   function deleteDB() {
      $this->cleanVlan('', $this->getValue('id'));
      $this->disconnectDB($this->getValue('id'));
      $this->oFusioninventory_networkport->deleteDB(); // fusioninventory
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
      $ptud = new PluginFusioninventoryUnknownDevice;
      $unknown_infos["name"] = '';
      $newID=$ptud->add($unknown_infos);
      // Add networking_port
      $np=new NetworkPort;
      $port_add["items_id"] = $newID;
      $port_add["itemtype"] = 'PluginFusioninventoryUnknownDevice';
      $port_add["ip"] = $p_ip;
      $port_add['mac'] = $p_mac;
      $dport = $np->add($port_add);
      $ptsnmp=new PluginFusinvsnmpSNMP;
      $this->connectDB($dport);
   }

   /**
    * Connect this port to another one in DB
    *
    *@param $destination_port id of destination port
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
    *@param $destination_port id of destination port
    *@return nothing
    **/
	function connectDB($destination_port='') {
		global $DB;

      //$ptap = new PluginFusioninventoryAgentProcess;

      $queryVerif = "SELECT *
                     FROM `glpi_networkports_networkports`
                     WHERE `networkports_id_1` IN ('".$this->getValue('id')."', '".$destination_port."')
                           AND `networkports_id_2` IN ('".$this->getValue('id')."', '".$destination_port."');";

      if ($resultVerif=$DB->query($queryVerif)) {
         if ($DB->numrows($resultVerif) == "0") { // no existing connection between those 2 ports
            $this->disconnectDB($this->getValue('id')); // disconnect this port
            $this->disconnectDB($destination_port);     // disconnect destination port
            $nn = new NetworkPort_NetworkPort();
            if ($nn->add(array('networkports_id_1'=> $this->getValue('id'),
                               'networkports_id_2' => $destination_port))) { //connect those 2 ports
               //$ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
               //                     array('query_nb_connections_created' => '1'));
               PluginFusinvsnmpNetworkPortLog::addLogConnection("make",$this->getValue('id'));
            }
         }
      }
   }

   /**
    * Disconnect a port in DB
    *
    *@param $p_port='' Port id to disconnect
    *@return nothing
    **/
	function disconnectDB($p_port='') {
      if ($p_port=='') $p_port=$this->getValue('id');
      $netwire = new NetworkPort_NetworkPort;
      PluginFusinvsnmpNetworkPortLog::addLogConnection("remove",$netwire->getOppositeContact($p_port));
      //PluginFusinvsnmpNetworkPortLog::addLogConnection("remove",$p_port);
      $nn = new NetworkPort_NetworkPort();
      if ($nn->getFromDBForNetworkPort($p_port)) {
         if ($nn->delete(array('id'=>$p_port))) {
            $ptap = new PluginFusioninventoryAgentProcess;
            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                 array('query_nb_connections_deleted' => '1'));
         }
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
    * Add MAC address
    *
    *@param $p_mac MAC address
    *@return nothing
    **/
   function addMac($p_mac) {
      $this->portMacs[]=$p_mac;
   }

   /**
    * Add IP address
    *
    *@param $p_ip IP address
    *@return nothing
    **/
   function addIp($p_ip) {
      $this->portIps[]=$p_ip;
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
         $this->connectedPort=$this->getConnectedPortInDB($this->getValue('id'));
      }
      $vlans = array();
      foreach ($this->portVlans as $vlan) {
         $vlans[] = Dropdown::importExternal("Vlan", $vlan['number'], 0, array(), $vlan['name']);
      }
      if (count($vlans)) { // vlans to add/update
         $ports[] = $this->getValue('id');
         if ($this->connectedPort != '') $ports[] = $this->connectedPort;
         foreach ($ports AS $num=>$tmp_port) {
            if ($num==1) { // connected port
               $ptpConnected = new PluginFusinvsnmpNetworkPort();
               $ptpConnected->load($tmp_port);
               if ($ptpConnected->fields['itemtype']==NETWORKING_TYPE) {
                  break; // don't update if port on a switch
               }
            }
            $query = "SELECT *
                      FROM `glpi_networkports_vlans`
                           LEFT JOIN `glpi_vlans`
                              ON `glpi_networkports_vlans`.`vlans_id`=`glpi_vlans`.`id`
                      WHERE `networkports_id`='$tmp_port'";
            if ($result=$DB->query($query)) {
               if ($DB->numrows($result) == "0") { // this port has no vlan
                  foreach ($vlans as $vlans_id) {
                     $this->assignVlan($tmp_port, $vlans_id);
                  }
               } else { // this port has one or more vlans
                  $vlansDB = array();
                  $vlansDBnumber = array();
                  $vlansToAssign = array();
                  while ($vlanDB=$DB->fetch_assoc($result)) {
                     $vlansDBnumber[] = $vlanDB['name'];
                     $vlansDB[] = array('number'=>$vlanDB['name'], 'name'=>$vlanDB['comment'],
                                        'id'=>$vlanDB['id']);
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
                     $this->cleanVlan($vlanToUnassign['id'], $tmp_port);
                  }
                  foreach ($vlansToAssign as $vlanToAssign) {
                     $vlans_id = Dropdown::importExternal("Vlan",
                                                       $vlanToAssign['number'], 0, array(),
                                                       $vlanToAssign['name']);
                     $this->assignVlan($tmp_port, $vlans_id);
                  }
               }
            }
         }
      } else { // no vlan to add/update --> delete existing
         $query = "SELECT *
                   FROM `glpi_networkports_vlans`
                   WHERE `networkports_id`='".$this->getValue('id')."'";
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) > 0) {// this port has one or more vlan
               $this->cleanVlan('', $this->getValue('id'));
               if ($this->connectedPort != '') {
                  $ptpConnected = new PluginFusinvsnmpNetworkPort();
                  $ptpConnected->load($this->connectedPort);
                  if ($ptpConnected->fields['itemtype'] != NETWORKING_TYPE) {
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
    *@param $p_port Port id
    *@param $p_vlan Vlan id
    *@return nothing
    **/
   function assignVlan($p_port, $p_vlan) {
      global $DB;

      $query = "INSERT INTO glpi_networkports_vlans (networkports_id,vlans_id)
                VALUES ('$p_port','$p_vlan')";
      $DB->query($query);
   }

   /**
    * Clean vlan
    *
    *@param $p_vlan Vlan id
    *@param $p_port='' Port id
    *@return nothing
    **/
   function cleanVlan($p_vlan, $p_port='') {
		global $DB;

      if ($p_vlan != '') {
         if ($p_port != '') { // delete this vlan for this port
            $query="DELETE FROM `glpi_networkports_vlans`
                    WHERE `vlans_id`='$p_vlan'
                          AND `networkports_id`='$p_port';";
         } else { // delete this vlan for all ports
            $query="DELETE FROM `glpi_networkports_vlans`
                    WHERE `vlans_id`='$p_vlan';";
            // do not remove vlan in glpi_vlans : manual remove
         }
      } else { // delete all vlans for this port
         $query="DELETE FROM `glpi_networkports_vlans`
                 WHERE `networkports_id`='$p_port';";
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
      $ptp = new PluginFusinvsnmpNetworkPort;
      foreach($this->portsToConnect as $index=>$portConnection) {
         if ($macs!='') $macs.=', ';
         $ptp->load($portConnection);
         $macs.="'".$ptp->getValue('mac')."'";
         $mac[$index]=$ptp->getValue('mac');
      }
      if ($macs!='') {
         $query = "SELECT `mac`
                   FROM `glpi_networkequipments`
                   WHERE `mac` IN (".$macs.");";
         $result=$DB->query($query);
         if ($DB->numrows($result) == 1) {
            $switch = $DB->fetch_assoc($result);
            return array_search($switch['mac'], $mac);
         }
      }
      return '';
   }

   /**
    * Get connected port in DB
    *
    *@param $p_portID Port id of first port
    *@return Port id of connected port or '' if no connection
    **/
   function getConnectedPortInDB($p_portID) {
      global $DB;

      $query = "SELECT `networkports_id_1` AS `id`
                FROM `glpi_networkports_networkports`
                WHERE `networkports_id_2`='".$p_portID."'
                UNION
                SELECT `networkports_id_2` AS `id`
                FROM `glpi_networkports_networkports`
                WHERE `networkports_id_1`='".$p_portID."';";
      $result=$DB->query($query);
      if ($DB->numrows($result) == 1) {
         $port = $DB->fetch_assoc($result);
         return $port['id'];
      }
      return '';
   }

   /**
    * Get ports to connect
    *
    *@return array of ports
    **/
   function getPortsToConnect() {
      return $this->portsToConnect;
   }

   /**
    * Get MAC addresses to connect
    *
    *@return array of MAC addresses
    **/
   function getMacsToConnect() {
      return $this->portMacs;
   }

   /**
    * Get IP addresses to connect
    *
    *@return array of IP addresses
    **/
   function getIpsToConnect() {
      return $this->portIps;
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

   /**
    * Get noTrunk
    *
    *@return true/false
    **/
   function getNoTrunk() {
      return $this->noTrunk;
   }

   /**
    * Set no trunk
    *
    *@return nothing
    **/
   function setNoTrunk() {
      $this->portsToConnect=array(); // no connection
      $this->unknownDevicesToConnect=array(); // no connection

      $this->noTrunk = true;
      $this->setValue('vlanTrunkPortDynamicStatus', -1);
   }

   /**
    * Is real port (not virtual or loopback)
    *
    *@return true/false
    **/
   function isReal($p_type) {
      $real = false;
      if ( (strstr($p_type, "ethernetCsmacd"))
            OR ($p_type == "6")
            OR ($p_type == "ethernet-csmacd(6)")
            OR (strstr($p_type, "iso88023Csmacd"))
            OR ($p_type == "7")
            OR ($p_type == "ieee80211(71)")        // wifi
            OR ($p_type == "ieee80211")            // wifi
            OR ($p_type == "71")                   // wifi
            OR ($p_type == "gigabitEthernet(117)")
            OR ($p_type == "gigabitEthernet")
            OR ($p_type == "117")
            OR ($p_type == "fastEther(62)")
            OR ($p_type == "fastEther")
            OR ($p_type == "62")
         ) { // not virtual port
         $real = true;
      }
      return $real;
   }

   static function getUniqueObjectfieldsByportID($id) {
      global $DB;

      $array = array();
      $query = "SELECT *
                FROM `glpi_networkports`
                WHERE `id`='".$id."';";
      if ($result=$DB->query($query)) {
         $data = $DB->fetch_array($result);
         $array["items_id"] = $data["items_id"];
         $array["itemtype"] = $data["itemtype"];
      }
      switch($array["itemtype"]) {
         case NETWORKING_TYPE:
            $query = "SELECT *
                      FROM `glpi_networkequipments`
                      WHERE `id`='".$array["itemtype"]."'
                      LIMIT 0,1;";
            if ($result=$DB->query($query)) {
               $data = $DB->fetch_array($result);
               $array["name"] = $data["name"];
            }
            break;
      }
      return($array);
   }


   
}

?>