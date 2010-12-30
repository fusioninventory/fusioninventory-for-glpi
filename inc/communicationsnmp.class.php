<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Purpose of file: management of snmp communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to communicate with agents using XML
 **/
class PluginFusinvsnmpCommunicationSNMP {
   private $sxml, $deviceId, $ptd;

   /**
    * Add AUTHENTICATION string to XML node
    *
    *@param $p_sxml_node XML node to authenticate
    *@param $p_id Authenticate id
    *@return nothing
    **/
   function addAuth($p_sxml_node, $p_id) {
      $ptsnmpa = new PluginFusinvsnmpConfigSecurity;
      $ptsnmpa->getFromDB($p_id);

      $sxml_authentication = $p_sxml_node->addChild('AUTHENTICATION');
         $sxml_authentication->addAttribute('ID', $p_id);
         $sxml_authentication->addAttribute('COMMUNITY', $ptsnmpa->fields['community']);
         $sxml_authentication->addAttribute('VERSION',
                           $ptsnmpa->getSNMPVersion($ptsnmpa->fields['snmpversion']));
         $sxml_authentication->addAttribute('USERNAME', $ptsnmpa->fields['username']);
         if ($ptsnmpa->fields['authentication'] == '0') {
            $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('AUTHPROTOCOL',
                           $ptsnmpa->getSNMPAuthProtocol($ptsnmpa->fields['authentication']));
         }
         $sxml_authentication->addAttribute('AUTHPASSPHRASE', $ptsnmpa->fields['auth_passphrase']);
         if ($ptsnmpa->fields['encryption'] == '0') {
            $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('PRIVPROTOCOL',
                           $ptsnmpa->getSNMPEncryption($ptsnmpa->fields['encryption']));
         }
         $sxml_authentication->addAttribute('PRIVPASSPHRASE', $ptsnmpa->fields['priv_passphrase']);
   }



   function addModel($p_sxml_node, $p_id) {
      $models = new PluginFusinvsnmpModel();
      $mib_networking = new PluginFusinvsnmpModelMib();

      $models->getFromDB($p_id);
      $sxml_model = $p_sxml_node->addChild('MODEL');
         $sxml_model->addAttribute('ID', $p_id);
         $sxml_model->addAttribute('NAME', $models->fields['name']);
         $mib_networking->oidList($sxml_model,$p_id);
   }


   function addGet($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_get = $p_sxml_node->addChild('GET');
         $sxml_get->addAttribute('OBJECT', $p_object);
         $sxml_get->addAttribute('OID', $p_oid);
         $sxml_get->addAttribute('VLAN', $p_vlan);
         $sxml_get->addAttribute('LINK', $p_link);
   }


   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }




   /**
    * Set XML code
    *
    *@param $p_xml XML code
    *@return nothing
    **/
   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA); // @ to avoid xml warnings
   }

   
   function addProcessNumber($p_pid) {
      $this->sxml->addChild('PROCESSNUMBER', $p_pid);
      //var_dump($this->sxml);
   }


   function searchDevice($a_criteria, $itemtype) {
      global $DB;

      $datacriteria = unserialize($_SESSION['plugin_fusinvsnmp_datacriteria']);

      switch ($itemtype) {

         case 'Computer':
         case 'Printer':
            $condition = "WHERE 1 ";
            $select = "`".getTableForItemType($itemtype)."`.`id`";
            $leftjoin = '';
            $input = array();

            foreach ($a_criteria as $criteria) {
               switch ($criteria) {

                 case 'serialnumber':
                     $condition .= "AND `serial`='".$datacriteria['serialnumber']."' ";
                     $select .= ", serial";
                     $input['serial'] = $datacriteria['serialnumber'];
                     break;

                  case 'mac':
                     $condition .= "AND `glpi_networkports`.`mac`='".$datacriteria['mac']."'
                        AND `itemtype` = '".$itemtype."'";
                     $select .= ", `glpi_networkports`.`mac`";
                     $leftjoin = 'LEFT JOIN `glpi_networkports` on `'.getTableForItemType($itemtype).'`.`id`=`items_id`';
                     break;

                  case 'model':
                     $condition .= "AND `models_id`='".$datacriteria['model']."' ";
                     $select .= ", models_id";
                     break;

                  case 'name':
                     $condition .= "AND `name`='".$datacriteria['name']."' ";
                     $select .= ", name";
                     $input['name'] = $datacriteria['name'];
                     break;
               }
            }

            $query = "SELECT ".$select." FROM `".getTableForItemType($itemtype)."`
               ".$leftjoin."
               ".$condition." ";
            $result = $DB->query($query);
            return array($result, $input);
            break;

         case 'NetworkEquipment':
            $condition = "WHERE 1 ";
            $select = "id";
            $input = array();

            foreach ($a_criteria as $criteria) {
               switch ($criteria) {

                 case 'serialnumber':
                     $condition .= "AND `serial`='".$datacriteria['serialnumber']."' ";
                     $select .= ", serial";
                     $input['serial'] = $datacriteria['serialnumber'];
                     break;

                  case 'mac':
                     $condition .= "AND `mac`='".$datacriteria['mac']."' ";
                     $select .= ", mac";
                     break;

                  case 'model':
                     $condition .= "AND `models_id`='".$datacriteria['model']."' ";
                     $select .= ", models_id";
                     break;

                  case 'name':
                     $condition .= "AND `name`='".$datacriteria['name']."' ";
                     $select .= ", name";
                     $input['serial'] = $datacriteria['name'];
                     break;
               }
            }

            $query = "SELECT ".$select." FROM `".getTableForItemType("NetworkEquipment")."`
               ".$condition." ";
            $result = $DB->query($query);
            return array($result, $input);
            break;

         
      }

      
      
   }

}

?>
