<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
   private $sxml, $deviceId;

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
                            Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpversions',
                                            $ptsnmpa->fields['plugin_fusioninventory_snmpversions_id']));
         $sxml_authentication->addAttribute('USERNAME', $ptsnmpa->fields['username']);
         if ($ptsnmpa->fields['authentication'] == '0') {
            $sxml_authentication->addAttribute('AUTHPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('AUTHPROTOCOL',
                            Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpprotocolauths',
                                            $ptsnmpa->fields['authentication']));
         }
         $sxml_authentication->addAttribute('AUTHPASSPHRASE', $ptsnmpa->fields['auth_passphrase']);
         if ($ptsnmpa->fields['encryption'] == '0') {
            $sxml_authentication->addAttribute('PRIVPROTOCOL', '');
         } else {
            $sxml_authentication->addAttribute('PRIVPROTOCOL',
                    Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpprotocolprivs',
                                    $ptsnmpa->fields['encryption']));
         }
         $sxml_authentication->addAttribute('PRIVPASSPHRASE', $ptsnmpa->fields['priv_passphrase']);
   }

   /**
    * Set XML code
    *
    *@param $p_xml XML code
    *@return nothing
    **/
   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml); // @ to avoid xml warnings
   }

   
   function addProcessNumber($p_pid) {
      $this->sxml->addChild('PROCESSNUMBER', $p_pid);
      //var_dump($this->sxml);
   }
}

?>
