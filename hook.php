<?php

/*
 * @version $Id: connection.function.php 6975 2008-06-13 15:43:18Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

function plugin_fusioninventory_getSearchOption() {
	global $LANG;
	$sopt = array ();

	$config = new PluginFusioninventoryConfig;

	// Part header
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE]['common'] = $LANG['plugin_fusioninventory']["errors"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][1]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][1]['field'] = 'ifaddr';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][1]['linkfield'] = 'ifaddr';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][1]['name'] = $LANG['plugin_fusioninventory']["errors"][1];

	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][30]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][30]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][3]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][3]['field'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][3]['linkfield'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][3]['name'] = $LANG["common"][1];

	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][4]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][4]['field'] = 'device_id';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][4]['linkfield'] = 'device_id';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][4]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][6]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][6]['field'] = 'description';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][6]['linkfield'] = 'description';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][6]['name'] = $LANG['plugin_fusioninventory']["errors"][2];
  $sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][6]['datatype']='text';
  
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][7]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][7]['field'] = 'first_pb_date';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][7]['linkfield'] = 'first_pb_date';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][7]['name'] = $LANG['plugin_fusioninventory']["errors"][3];
  $sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][7]['datatype']='datetime';
  
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][8]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][8]['field'] = 'last_pb_date';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][8]['linkfield'] = 'last_pb_date';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][8]['name'] = $LANG['plugin_fusioninventory']["errors"][4];
  $sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][8]['datatype']='datetime';
  
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][80]['table'] = 'glpi_entities';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][80]['field'] = 'completename';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][80]['linkfield'] = 'entities_id';
	$sopt[PLUGIN_FUSIONINVENTORY_ERROR_TYPE][80]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL]['common'] = $LANG['plugin_fusioninventory']["profile"][19];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][1]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][1]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][1]['linkfield'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_FUSIONINVENTORY_MODEL][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][30]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][30]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][3]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][3]['field'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][3]['linkfield'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][3]['name'] = $LANG["common"][17];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][5]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][5]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][5]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][5]['name'] = $LANG["buttons"][31];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][6]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][6]['field'] = 'activation';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][6]['linkfield'] = 'activation';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][6]['name'] = $LANG['plugin_fusioninventory']["model_info"][11];
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][6]['datatype']='bool';

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][7]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][7]['field'] = 'discovery_key';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][7]['linkfield'] = 'discovery_key';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][7]['name'] = $LANG['plugin_fusioninventory']["model_info"][12];

	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][8]['table'] = 'glpi_plugin_fusioninventory_model_infos';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][8]['field'] = 'comment';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][8]['linkfield'] = 'comment';
	$sopt[PLUGIN_FUSIONINVENTORY_MODEL][8]['name'] = $LANG['common'][25];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH]['common'] = $LANG['plugin_fusioninventory']["profile"][22];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][1]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][1]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][1]['linkfield'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][30]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][30]['linkfield'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][3]['table'] = 'glpi_plugin_fusioninventory_snmpversions';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][3]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][3]['linkfield'] = 'plugin_fusioninventory_snmpversions_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][3]['name'] = $LANG['plugin_fusioninventory']["model_info"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][4]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][4]['field'] = 'community';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][4]['linkfield'] = 'community';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][4]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][1];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][5]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][5]['field'] = 'sec_name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][5]['linkfield'] = 'sec_name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][5]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][7]['table'] = 'glpi_plugin_fusioninventory_snmpprotocolauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][7]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][7]['linkfield'] = 'auth_protocol';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][7]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][4];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][8]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][8]['field'] = 'auth_passphrase';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][8]['linkfield'] = 'auth_passphrase';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][8]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][5];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][9]['table'] = 'glpi_plugin_fusioninventory_snmpprotocolprivs';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][9]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][9]['linkfield'] = 'priv_protocol';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][9]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][6];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][10]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][10]['field'] = 'priv_passphrase';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][10]['linkfield'] = 'priv_passphrase';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AUTH][10]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][7];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN]['common'] = $LANG['plugin_fusioninventory']["menu"][4];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][1]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][1]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][1]['linkfield'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][1]['datatype']='itemlink';

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][2]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][2]['field'] = 'dnsname';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][2]['linkfield'] = 'dnsname';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][2]['name'] = $LANG['plugin_fusioninventory']["unknown"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][3]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][3]['field'] = 'date_mod';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][3]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][3]['name'] = $LANG["common"][26];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][4]['table'] = 'glpi_entities';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][4]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][4]['linkfield'] = 'entities_id';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][4]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][5]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][5]['field'] = 'serial';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][5]['linkfield'] = 'serial';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][5]['name'] = $LANG['common'][19];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][6]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][6]['field'] = 'otherserial';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][6]['linkfield'] = 'otherserial';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][6]['name'] = $LANG['common'][20];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][7]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][7]['field'] = 'contact';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][7]['linkfield'] = 'contact';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][7]['name'] = $LANG['common'][18];
 
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][8]['table'] = 'glpi_dropdown_domain';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][8]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][8]['linkfield'] = 'domain';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][8]['name'] = $LANG["setup"][89];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][9]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][9]['field'] = 'comment';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][9]['linkfield'] = 'comment';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][9]['name'] = $LANG['common'][25];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][10]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][10]['field'] = 'type';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][10]['linkfield'] = 'type';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][10]['name'] = $LANG['common'][17];

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][11]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][11]['field'] = 'snmp';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][11]['linkfield'] = 'snmp';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][11]['name'] = $LANG['plugin_fusioninventory']["functionalities"][3];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][11]['datatype']='bool';
   
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][12]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][12]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][12]['linkfield'] = 'plugin_fusioninventory_modelinfos_id';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][12]['name'] = $LANG['plugin_fusioninventory']["model_info"][4];

   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][13]['table'] = 'glpi_plugin_fusioninventory_snmpauths';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][13]['field'] = 'name';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][13]['linkfield'] = 'plugin_fusioninventory_snmpauths_id';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][13]['name'] = $LANG['plugin_fusioninventory']["model_info"][3];

   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][14]['table'] = 'glpi_networking_ports';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][14]['field'] = 'ifaddr';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][14]['linkfield'] = 'ID';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][14]['name'] = $LANG["networking"][14];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][14]['forcegroupby']='1';

   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][15]['table'] = 'glpi_networking_ports';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][15]['field'] = 'ifmac';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][15]['linkfield'] = 'ID';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][15]['name'] = $LANG["networking"][15];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][15]['forcegroupby']='1';

   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][16]['table'] = 'glpi_networking';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][16]['field'] = 'device';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][16]['linkfield'] = 'device';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][16]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
   //$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][16]['forcegroupby'] = '1';

   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][17]['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][17]['field'] = 'ID';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][17]['linkfield'] = 'ID';
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][17]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][17]['forcegroupby'] = '1';

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][18]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][18]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][18]['linkfield'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][18]['name'] = $LANG['plugin_fusioninventory']["unknown"][1];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][18]['forcegroupby']='1';

	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][19]['table'] = 'glpi_plugin_fusioninventory_unknown_device';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][19]['field'] = 'accepted';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][19]['linkfield'] = 'accepted';
	$sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][19]['name'] = $LANG['plugin_fusioninventory']["unknown"][2];
   $sopt[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN][19]['datatype']='bool';

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS]['common'] = $LANG['plugin_fusioninventory']["errors"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][2]['name'] = $LANG['plugin_fusioninventory']["snmp"][42];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][3]['name'] = $LANG['plugin_fusioninventory']["snmp"][43];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][4]['name'] = $LANG['plugin_fusioninventory']["snmp"][44];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][5]['name'] = $LANG['plugin_fusioninventory']["snmp"][45];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][6]['name'] = $LANG['plugin_fusioninventory']["snmp"][46];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][7]['name'] = $LANG['plugin_fusioninventory']["snmp"][47];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][8]['name'] = $LANG['plugin_fusioninventory']["snmp"][48];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][9]['name'] = $LANG['plugin_fusioninventory']["snmp"][49];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][10]['name'] = $LANG['plugin_fusioninventory']["snmp"][51];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][11]['name'] = $LANG['plugin_fusioninventory']["mapping"][115];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][12]['name'] = $LANG["networking"][17];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][13]['name'] = $LANG['plugin_fusioninventory']["snmp"][50];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][14]['name'] = $LANG["networking"][56];

   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS][15]['name'] = $LANG['plugin_fusioninventory']["snmp"][41];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS]['common'] = $LANG['plugin_fusioninventory']["profile"][26];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][1]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][1]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][1]['linkfield'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][30]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][30]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][4]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][4]['field'] = 'threads_discovery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][4]['linkfield'] = 'threads_discovery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][4]['name'] = $LANG['plugin_fusioninventory']["agents"][3];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][6]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][6]['field'] = 'threads_query';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][6]['linkfield'] = 'threads_query';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][6]['name'] = $LANG['plugin_fusioninventory']["agents"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][8]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][8]['field'] = 'last_agent_update';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][8]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][8]['name'] = $LANG['plugin_fusioninventory']["agents"][4];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][8]['datatype']='datetime';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][9]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][9]['field'] = 'fusioninventory_agent_version';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][9]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][9]['name'] = $LANG['plugin_fusioninventory']["agents"][5];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][10]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][10]['field'] = 'lock';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][10]['linkfield'] = 'lock';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][10]['name'] = $LANG['plugin_fusioninventory']["agents"][6];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][10]['datatype']='bool';

 	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][11]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][11]['field'] = 'module_inventory';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][11]['linkfield'] = 'module_inventory';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][11]['name'] = $LANG['plugin_fusioninventory']['config'][3];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][11]['datatype']='bool';

 	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][12]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][12]['field'] = 'module_netdiscovery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][12]['linkfield'] = 'module_netdiscovery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][12]['name'] = $LANG['plugin_fusioninventory']['config'][4];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][12]['datatype']='bool';

   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][13]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][13]['field'] = 'module_snmpquery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][13]['linkfield'] = 'module_snmpquery';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][13]['name'] = $LANG['plugin_fusioninventory']['config'][7];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][13]['datatype']='bool';

   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][14]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][14]['field'] = 'module_wakeonlan';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][14]['linkfield'] = 'module_wakeonlan';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][14]['name'] = $LANG['plugin_fusioninventory']['config'][6];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_AGENTS][14]['datatype']='bool';

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP]['common'] = $LANG['plugin_fusioninventory']["profile"][25];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][1]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][1]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][1]['linkfield'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][2]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][2]['field'] = 'ifaddr_start';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][2]['linkfield'] = 'ifaddr_start';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][2]['name'] = $LANG['plugin_fusioninventory']["rangeip"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][3]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][3]['field'] = 'ifaddr_end';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][3]['linkfield'] = 'ifaddr_end';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][3]['name'] = $LANG['plugin_fusioninventory']["rangeip"][1];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][30]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][30]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['linkfield'] = 'plugin_fusioninventory_agents_id_discover';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['name'] = $LANG['plugin_fusioninventory']["agents"][12];
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['datatype']='itemlink';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['itemlink_type']=PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][5]['forcegroupby']='1';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][6]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][6]['field'] = 'discover';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][6]['linkfield'] = 'discover';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][6]['name'] = $LANG['plugin_fusioninventory']["discovery"][3];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][6]['datatype']='bool';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][7]['table'] = 'glpi_plugin_fusioninventory_rangeip';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][7]['field'] = 'query';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][7]['linkfield'] = 'query';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][7]['name'] = $LANG['plugin_fusioninventory']["rangeip"][3];
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][7]['datatype']='bool';
  
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][8]['table'] = 'glpi_entities';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][8]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][8]['linkfield'] = 'entities_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][8]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['linkfield'] = 'plugin_fusioninventory_agents_id_query';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['name'] = $LANG['plugin_fusioninventory']["agents"][13];
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['datatype']='itemlink';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['itemlink_type']=PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
   $sopt[PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP][9]['forcegroupby']='1';

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY]['common'] = $LANG['plugin_fusioninventory']["title"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][1]['table'] = 'glpi_plugin_fusioninventory_snmphistories';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][1]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][1]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][1]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][2]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][2]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][2]['linkfield'] = 'networkports_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][2]['name'] = $LANG["setup"][175];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][3]['table'] = 'glpi_plugin_fusioninventory_snmphistories';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][3]['field'] = 'Field';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][3]['linkfield'] = 'Field';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][3]['name'] = $LANG["event"][18];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][4]['table'] = 'glpi_plugin_fusioninventory_snmphistories';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][4]['field'] = 'old_value';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][4]['linkfield'] = 'old_value';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][4]['name'] = $LANG['plugin_fusioninventory']["history"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][5]['table'] = 'glpi_plugin_fusioninventory_snmphistories';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][5]['field'] = 'new_value';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][5]['linkfield'] = 'new_value';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][5]['name'] = $LANG['plugin_fusioninventory']["history"][1];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][6]['table'] = 'glpi_plugin_fusioninventory_snmphistories';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][6]['field'] = 'date_mod';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][6]['linkfield'] = 'date_mod';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][6]['name'] = $LANG["common"][27];
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_HISTORY][6]['datatype']='datetime';


	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2]['common'] = $LANG['plugin_fusioninventory']["profile"][28];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][30]['table'] = 'glpi_plugin_fusioninventory_networking_ports';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][30]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][30]['linkfield'] = '';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][30]['name'] = $LANG["reports"][52];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][1]['table'] = 'glpi_plugin_fusioninventory_networking_ports';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][1]['field'] = 'networkports_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][1]['linkfield'] = 'networkports_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][1]['name'] = $LANG["setup"][175];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][2]['table'] = 'glpi_dropdown_locations';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][2]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][2]['linkfield'] = 'networkports_id';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][2]['name'] = $LANG["common"][15];

	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][3]['table'] = 'glpi_plugin_fusioninventory_networking_ports';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][3]['field'] = 'lastup';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][3]['linkfield'] = 'lastup';
	$sopt[PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2][3]['name'] = $LANG["login"][0];


	$sopt[NETWORKING_TYPE][5190]['table']='glpi_plugin_fusioninventory_modelinfos';
	$sopt[NETWORKING_TYPE][5190]['field']='ID';
	$sopt[NETWORKING_TYPE][5190]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5190]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[NETWORKING_TYPE][5191]['table'] = 'glpi_plugin_fusioninventory_networking';
		$sopt[NETWORKING_TYPE][5191]['field'] = 'plugin_fusioninventory_snmpauths_id';
		$sopt[NETWORKING_TYPE][5191]['linkfield'] = 'ID';
		$sopt[NETWORKING_TYPE][5191]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	} else {
		$sopt[NETWORKING_TYPE][5191]['table']='glpi_plugin_fusioninventory_snmpauths';
		$sopt[NETWORKING_TYPE][5191]['field']='name';
		$sopt[NETWORKING_TYPE][5191]['linkfield']='ID';
		$sopt[NETWORKING_TYPE][5191]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	}

	$sopt[NETWORKING_TYPE][5194]['table']='glpi_plugin_fusioninventory_networking';
	$sopt[NETWORKING_TYPE][5194]['field']='networkequipments_id';
	$sopt[NETWORKING_TYPE][5194]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5194]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][53];

	$sopt[NETWORKING_TYPE][5195]['table']='glpi_plugin_fusioninventory_networking';
	$sopt[NETWORKING_TYPE][5195]['field']='cpu';
	$sopt[NETWORKING_TYPE][5195]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5195]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][13];


	$sopt[PRINTER_TYPE][5190]['table']='glpi_plugin_fusioninventory_modelinfos';
	$sopt[PRINTER_TYPE][5190]['field']='ID';
	$sopt[PRINTER_TYPE][5190]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5190]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[PRINTER_TYPE][5191]['table'] = 'glpi_plugin_fusioninventory_printers';
		$sopt[PRINTER_TYPE][5191]['field'] = 'plugin_fusioninventory_snmpauths_id';
		$sopt[PRINTER_TYPE][5191]['linkfield'] = 'ID';
		$sopt[PRINTER_TYPE][5191]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	} else {
		$sopt[PRINTER_TYPE][5191]['table']='glpi_plugin_fusioninventory_snmpauths';
		$sopt[PRINTER_TYPE][5191]['field']='ID';
		$sopt[PRINTER_TYPE][5191]['linkfield']='ID';
		$sopt[PRINTER_TYPE][5191]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	}

	$sopt[PRINTER_TYPE][5194]['table']='glpi_plugin_fusioninventory_printers';
	$sopt[PRINTER_TYPE][5194]['field']='printers_id';
	$sopt[PRINTER_TYPE][5194]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5194]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][53];

	$sopt[PRINTER_TYPE][5196]['table']='glpi_plugin_fusioninventory_networking';
	$sopt[PRINTER_TYPE][5196]['field']='ID';
	$sopt[PRINTER_TYPE][5196]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5196]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
	$sopt[PRINTER_TYPE][5196]['forcegroupby']='1';

	$sopt[PRINTER_TYPE][5197]['table']='glpi_plugin_fusioninventory_networking_ports';
	$sopt[PRINTER_TYPE][5197]['field']='ID';
	$sopt[PRINTER_TYPE][5197]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5197]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
	$sopt[PRINTER_TYPE][5197]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5192]['table']='glpi_plugin_fusioninventory_networking';
	$sopt[COMPUTER_TYPE][5192]['field']='ID';
	$sopt[COMPUTER_TYPE][5192]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5192]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
	$sopt[COMPUTER_TYPE][5192]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5193]['table']='glpi_plugin_fusioninventory_networking_ports';
	$sopt[COMPUTER_TYPE][5193]['field']='ID';
	$sopt[COMPUTER_TYPE][5193]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5193]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
	$sopt[COMPUTER_TYPE][5193]['forcegroupby']='1';



	$sopt[PLUGIN_FUSIONINVENTORY_TASK]['common'] = $LANG['plugin_fusioninventory']["task"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_TASK][1]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][1]['field'] = 'id';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][1]['linkfield'] = 'id';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_FUSIONINVENTORY_TASK][1]['datatype']='itemlink';

	$sopt[PLUGIN_FUSIONINVENTORY_TASK][2]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][2]['field'] = 'date';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][2]['linkfield'] = 'date';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][2]['name'] = $LANG["common"][27];
   $sopt[PLUGIN_FUSIONINVENTORY_TASK][2]['datatype']='datetime';

 	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['linkfield'] = 'agent_id';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['name'] = $LANG['plugin_fusioninventory']["agents"][13];
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['datatype']='itemlink';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['itemlink_type']=PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
   $sopt[PLUGIN_FUSIONINVENTORY_TASK][3]['forcegroupby']='1';

	$sopt[PLUGIN_FUSIONINVENTORY_TASK][4]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][4]['field'] = 'action';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][4]['linkfield'] = 'action';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][4]['name'] = $LANG['plugin_fusioninventory']["task"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_TASK][5]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][5]['field'] = 'param';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][5]['linkfield'] = 'param';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][5]['name'] = $LANG['plugin_fusioninventory']["task"][2];
   
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][6]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][6]['field'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][6]['linkfield'] = 'itemtype';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][6]['name'] = $LANG["common"][1];
 
//	$sopt[PLUGIN_FUSIONINVENTORY_TASK][7]['table'] = 'glpi_plugin_fusioninventory_task';
//	$sopt[PLUGIN_FUSIONINVENTORY_TASK][7]['field'] = 'on_device';
//	$sopt[PLUGIN_FUSIONINVENTORY_TASK][7]['linkfield'] = 'on_device';
//	$sopt[PLUGIN_FUSIONINVENTORY_TASK][7]['name'] = $LANG["common"][27];

	$sopt[PLUGIN_FUSIONINVENTORY_TASK][8]['table'] = 'glpi_plugin_fusioninventory_task';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][8]['field'] = 'single';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][8]['linkfield'] = 'single';
	$sopt[PLUGIN_FUSIONINVENTORY_TASK][8]['name'] = $LANG['plugin_fusioninventory']["task"][3];



	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE]['common'] = $LANG['plugin_fusioninventory']["constructdevice"][0];

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][1]['table'] = 'glpi_plugin_fusioninventory_construct_device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][1]['field'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][1]['linkfield'] = 'ID';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][1]['datatype']='itemlink';

  	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][2]['table'] = 'glpi_dropdown_manufacturer';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][2]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][2]['linkfield'] = 'manufacturer';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][2]['name'] = $LANG['common'][5];

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][3]['table'] = 'glpi_plugin_fusioninventory_construct_device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][3]['field'] = 'device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][3]['linkfield'] = 'device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][3]['name'] = $LANG['common'][1];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][3]['datatype']='text';

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][4]['table'] = 'glpi_plugin_fusioninventory_construct_device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][4]['field'] = 'firmware';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][4]['linkfield'] = 'firmware';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][4]['name'] = $LANG['setup'][71];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][4]['datatype']='text';

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][5]['table'] = 'glpi_plugin_fusioninventory_construct_device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][5]['field'] = 'sysdescr';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][5]['linkfield'] = 'sysdescr';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][5]['name'] = $LANG['common'][25];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][5]['datatype']='text';

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][6]['table'] = 'glpi_plugin_fusioninventory_construct_device';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][6]['field'] = 'type';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][6]['linkfield'] = 'type';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][6]['name'] = $LANG['common'][17];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][6]['datatype']='number';

	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][7]['table'] = 'glpi_plugin_fusioninventory_modelinfos';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][7]['field'] = 'name';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][7]['linkfield'] = 'snmpmodel_id';
	$sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][7]['name'] = $LANG['plugin_fusioninventory']["profile"][24];
   $sopt[PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE][7]['datatype']='itemptype';

   

	return $sopt;
}


function plugin_fusioninventory_giveItem($type,$ID,$data,$num) {
	global $CFG_GLPI, $DB, $INFOFORM_PAGES, $LINK_ID_TABLE,$LANG,$SEARCH_OPTION,$FUSIONINVENTORY_MAPPING;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "GiveItem : ".$field."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					$Networkport = new Networkport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $Networkport->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$vartmp."\">";
                  $out .=  $Networkport->device_name;
                  $out .= $vartmp;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Networkport;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
					}
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.'.'.$field) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networking.networkequipments_id" :
					$query = "SELECT *
                         FROM `glpi_plugin_fusioninventory_networking`
                         WHERE `networkequipments_id` = '".$data["ID"]."';";
					if ($result = $DB->query($query)) {
						$data2=$DB->fetch_array($result);
               }

					$last_date = "";
					if (isset($data2["last_fusioninventory_update"])) {
						$last_date = $data2["last_fusioninventory_update"];
               }
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySNMP;
					$FK_model_DB = $plugin_fusioninventory_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_modelinfos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.name" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySnmpauth;
					$FK_auth_DB = $plugin_fusioninventory_snmp->GetSNMPAuth($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/snmp_auth.form.php?ID=" . $FK_auth_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpauths", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               $Networkport = new Networkport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $Networkport->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$vartmp."\">";
                  $out .=  $Networkport->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Networkport;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.printers_id" :
					$query = "SELECT *
                         FROM `glpi_plugin_fusioninventory_printers`
                         WHERE `printers_id` = '".$data["ID"]."';";
					if ($result = $DB->query($query)) {
						$data2=$DB->fetch_array($result);
               }

					$last_date = "";
					if (isset($data2["last_fusioninventory_update"])) {
						$last_date = $data2["last_fusioninventory_update"];
               }
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySNMP;
					$FK_model_DB = $plugin_fusioninventory_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_modelinfos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.ID" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySnmpauth;
					$FK_auth_DB = $plugin_fusioninventory_snmp->GetSNMPAuth($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/snmp_auth.form.php?ID=" . $FK_auth_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpauths", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * Model List (plugins/fusioninventory/front/models.php)
		case PLUGIN_FUSIONINVENTORY_MODEL :
			switch ($table.'.'.$field) {

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_fusioninventory_modelinfos.itemtype" :
					$out = '<center> ';
					switch ($data["ITEM_$num"]) {
						case COMPUTER_TYPE:
							$out .= $LANG["Menu"][0];
							break;

						case NETWORKING_TYPE:
							$out .= $LANG["Menu"][1];
							break;

						case PRINTER_TYPE:
							$out .= $LANG["Menu"][2];
							break;

						case PERIPHERAL_TYPE:
							$out .= $LANG["Menu"][16];
							break;

						case PHONE_TYPE:
							$out .= $LANG["Menu"][34];
							break;
					}
					$out .= '</center>';
					return $out;
					break;

				// ** Display pic / link for exporting model
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/fusioninventory/front/models.export.php' target='_blank'>
						<input type='hidden' name='model' value='" . $data["ID"] . "' />
						<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
						</form></div>";
					return "<center>".$out."</center>";
					break;

			}
			break;


		// * Authentification List (plugins/fusioninventory/front/snmp_auth.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_AUTH :
			switch ($table.'.'.$field) {

				// ** Hidden auth passphrase (SNMP v3)
				case "glpi_plugin_fusioninventory_snmpauths.auth_passphrase" :
               $out = "";
					if (empty($data["ITEM_$num"])) {
						
               } else {
						$out = "********";
               }
					return $out;
					break;

				// ** Hidden priv passphrase (SNMP v3)
				case "glpi_plugin_fusioninventory_snmpauths.priv_passphrase" :
               $out = "";
					if (empty($data["ITEM_$num"])) {
						
               } else {
						$out = "********";
               }
					return $out;
					break;
			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               $Networkport = new Networkport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $Networkport->getDeviceData($vartmp,PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN]."?ID=".$vartmp."\">";
                  $out .=  $Networkport->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Networkport;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

            case "glpi_plugin_fusioninventory_unknown_device.type" :
					$out = '<center> ';
					switch ($data["ITEM_$num"]) {
						case COMPUTER_TYPE:
							$out .= $LANG["Menu"][0];
							break;

						case NETWORKING_TYPE:
							$out .= $LANG["Menu"][1];
							break;

						case PRINTER_TYPE:
							$out .= $LANG["Menu"][2];
							break;

						case PERIPHERAL_TYPE:
							$out .= $LANG["Menu"][16];
							break;

						case PHONE_TYPE:
							$out .= $LANG["Menu"][34];
							break;
					}
					$out .= '</center>';
					return $out;
					break;


			}
			break;

		// *
		case PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS :
			switch ($table.'.'.$field) {

			}
			break;

		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2 :
			switch ($table.'.'.$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
					$query = "SELECT `glpi_networking`.`name` AS `name`, `glpi_networking`.`ID` AS `ID`
                         FROM `glpi_networking`
                              LEFT JOIN `glpi_networking_ports`
                                        ON `on_device` = `glpi_networking`.`ID`
                              LEFT JOIN `glpi_plugin_fusioninventory_networking_ports`
                                        ON `glpi_networking_ports`.`ID`=`networkports_id`
                         WHERE `glpi_plugin_fusioninventory_networking_ports`.`ID`='".$data["ITEM_$num"]."'
                         LIMIT 0,1;";
					$result = $DB->query($query);
					$data2 = $DB->fetch_assoc($result);
					$out = "<a href='".GLPI_ROOT."/front/networking.form.php?ID=".$data2["ID"]."'>".$data2["name"]."</a>";
				return "<center>".$out."</center>";
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_fusioninventory_networking_ports.networkports_id" :
					$Networkport=new Networkport;
					$Networkport->getFromDB($data["ITEM_$num"]);
               $name = "";
               if (isset($Networkport->fields["name"])) {
                  $name = $Networkport->fields["name"];
               }
					$out = "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$name."</a>";
					return "<center>".$out."</center>";
					break;

				// ** Location of switch
				case "glpi_dropdown_locations.ID" :
					$out = Dropdown::getDropdownName("glpi_dropdown_locations",$data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * FusionInventory Agents list (plugins/fusioninventory/front/agents.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_AGENTS :
			break;

		// * range IP list (plugins/fusioninventory/front/rangeip.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP :
			switch ($table.'.'.$field) {


				// ** Display entity name
				case "glpi_entities.name" :
					if ($data["ITEM_$num"] == '') {
						$out = Dropdown::getDropdownName("glpi_entities",$data["ITEM_$num"]);
						return "<center>".$out."</center>";
					}
					break;

			}
			break;

		// * Detail of ports history (plugins/fusioninventory/report/switch_ports.history.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_HISTORY :
			switch ($table.'.'.$field) {

				// ** Display switch and Port
				case "glpi_networking_ports.ID" :
					$Array_device = PluginFusioninventoryPort::getUniqueObjectfieldsByportID($data["ITEM_$num"]);
					$CommonItem = new CommonItem;
					$CommonItem->getFromDB($Array_device["itemtype"], $Array_device["on_device"]);
					$out = "<div align='center'>" . $CommonItem->getLink(1);

					$query = "SELECT *
                         FROM `glpi_networking_ports`
                         WHERE `ID`='" . $data["ITEM_$num"] . "';";
					$result = $DB->query($query);

					if ($DB->numrows($result) != "0") {
						$out .= "<br/><a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$DB->result($result, 0, "name")."</a>";
               }
					$out .= "</td>";
					return $out;
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_snmphistories.Field" :
               $out = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$data["ITEM_$num"]]['name'];
               return $out;
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_fusioninventory_snmphistories.old_value" :
					// TODO ADD LINK TO DEVICE
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_fusioninventory_snmphistories.new_value" :
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
					break;

			}
			break;

	}

	return "";
}

// Define Dropdown tables to be manage in GLPI :
function plugin_fusioninventory_getDropdown() {
	// Table => Name
	global $LANG;
	if (isset ($_SESSION["glpi_plugin_fusioninventory_installed"]) && $_SESSION["glpi_plugin_fusioninventory_installed"] == 1) {
		return array (
			"glpi_plugin_fusioninventory_snmpversions" => "SNMP version",
			"glpi_plugin_fusioninventory_mib_oid" => "OID MIB",
			"glpi_plugin_fusioninventory_mib_object" => "Objet MIB",
			"glpi_plugin_fusioninventory_mib_label" => "Label MIB"
		);
   } else {
		return array ();
   }
}

/* Cron */
function cron_plugin_fusioninventory() {
   // TODO :Disable for the moment (may be check if functions is good or not
//	$ptud = new PluginFusioninventoryUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
//	$ptud->FusionUnknownKnownDevice();
//   #Clean server script processes history
   $pfisnmph = new PluginFusioninventorySnmphistory;
   $pfisnmph->cronCleanHistory();
   return 1;
}



function plugin_fusioninventory_install() {
	global $DB, $LANG, $CFG_GLPI;

	include_once (GLPI_ROOT."/inc/profile.class.php");
    /**
    *  List of all FusionInventorys versions :
    *    1.0.0
    *    1.1.0 non exists glpi_plugin_fusioninventory_agents (MySQL)
    *    2.0.0 non exists glpi_plugin_fusioninventory_config_discovery (MySQL)
    *    2.0.1 Nothing
    *    2.0.2 config version field 2.0.2
    *    2.1.0 config version field 2.1.0
    *    2.1.1 config version field 2.1.1
    **/
   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config"))) {
      PluginFusioninventorySetup::install("2.2.0");
   } else if (TableExists("glpi_plugin_tracker_config")) {
      //$config = new PluginFusioninventoryConfig;
      if (!TableExists("glpi_plugin_tracker_agents")) {
         PluginFusioninventorySetup::update("1.1.0");
      }
      if (!TableExists("glpi_plugin_tracker_config_discovery")) {
         PluginFusioninventorySetup::update("2.0.0");
      }
      if (!FieldExists("glpi_plugin_tracker_config", "version")) {
         PluginFusioninventorySetup::update("2.0.2");
      }
      if (FieldExists("glpi_plugin_tracker_config", "version")) {
         $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";
         $result = $DB->query($query);
			$data = $DB->fetch_assoc($result);
         if  ($data['version'] == "0") {
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.0.2'
                        WHERE `ID`='1';");
            $data['version'] = "2.0.2";
         }
         if ($data['version'] == "2.0.2") {
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.0'
                        WHERE `ID`='1';");
            $data['version'] = "2.1.0";
         }
         if ($data['version'] == "2.1.0") {
            PluginFusioninventorySetup::update("2.1.1");
            $DB->query("UPDATE `glpi_plugin_tracker_config` 
                        SET version = '2.1.1'
                        WHERE ID=1");
            $data['version'] = "2.1.1";
         }
         if ($data['version'] == "2.1.1") {
            //PluginFusioninventorySetup::update("2.1.2");
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.2'
                        WHERE `ID`='1';");
            $data['version'] = "2.1.2";
         }
         if ($data['version'] == "2.1.2") {
            //PluginFusioninventorySetup::update("2.1.2");
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.3'
                        WHERE `ID`='1';");
            $data['version'] = "2.1.3";
         }
         if ($data['version'] == "2.1.3") {
            PluginFusioninventorySetup::update("2.2.0");
            $DB->query("UPDATE `glpi_plugin_fusioninventory_config`
                        SET `version` = '2.2.0'
                        WHERE `ID`='1';");
         }
      }
   } else if (TableExists("glpi_plugin_fusioninventory_config")) {

   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_fusioninventory_uninstall() {
   return PluginFusioninventorySetup::uninstall();
}

/**
* Check if FusionInventory need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_fusioninventory_needUpdate() {

    /**
    *  List of all FusionInventorys versions :
    *    1.0.0
    *    1.1.0 non exists glpi_plugin_fusioninventory_agents (MySQL)
    *    2.0.0 non exists glpi_plugin_fusioninventory_config_discovery (MySQL)
    *    2.0.1 Nothing
    *    2.0.2 config version field 2.0.2
    *    2.1.0 config version field 2.1.0
    *    2.1.1 config version field 2.1.1
    **/
	if (!TableExists("glpi_plugin_fusioninventory_config")) {
		return 0; // Installation
   } else if (!TableExists("glpi_plugin_fusioninventory_agents")) {
		return 1; //Update
   } else if (!TableExists("glpi_plugin_fusioninventory_config_discovery")) {
//		return 1; // Update (Bug with new version SVN 2.1.4
   } else if (!FieldExists("glpi_plugin_fusioninventory_config", "version")) {
      return 1; // Update
   } else if (FieldExists("glpi_plugin_fusioninventory_config", "version")) {
      $config = new PluginFusioninventoryConfig;
      if ($config->getValue('version') != "2.1.3") {
         return 1;
      } else {
         return 0;
      }
   } else {
		return 0;
   }
}



// Define headings added by the plugin //
function plugin_get_headings_fusioninventory($type,$ID,$withtemplate) {
	global $LANG;
	$configModules = new PluginFusioninventoryConfigModules;

	switch ($type) {
		case COMPUTER_TYPE :
			if ($withtemplate) { //?
				return array();
			// Non template case
         } else {
//				if ((PluginFusioninventory::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
				$array = array ();
            //return array(
            if (($configModules->isActivated('remotehttpagent')) AND(PluginFusioninventory::haveRight("remotecontrol","w"))) {
               $array[1] = $LANG['plugin_fusioninventory']["title"][0];
            }
				//}
            $array[2] = $LANG['plugin_fusioninventory']["title"][5];

            return $array;
//				}
			}
			break;

		case MONITOR_TYPE :
			if ($withtemplate) { //?
				return array();
			// Non template case
         } else {
//				if ((PluginFusioninventory::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
					return array(
						1 => $LANG['plugin_fusioninventory']["title"][5]
					);
//				}
			}
			break;

		case NETWORKING_TYPE :
			if ($withtemplate) {
				return array();
			// Non template case
         } else {
            $array = array ();
				if ((PluginFusioninventory::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
					$array[1] = $LANG['plugin_fusioninventory']["title"][0];
				}
            $array[2] = $LANG['plugin_fusioninventory']["title"][5];
            return $array;
			}
			break;

		case PRINTER_TYPE :
			// template case
			if ($withtemplate) {
				return array();
			// Non template case
         } else {
            $array = array ();
				if ((PluginFusioninventory::haveRight("snmp_printers", "r")) AND ($configModules->getValue("snmp") == "1")) {
					$array[1] = $LANG['plugin_fusioninventory']["title"][0];
				}
            $array[2] = $LANG['plugin_fusioninventory']["title"][5];
            return $array;
			}
			break;

		case PROFILE_TYPE :
			// template case
			if ($withtemplate) {
				return array();
			// Non template case
         } else {
				return array(
					1 => $LANG['plugin_fusioninventory']["title"][0],
					);
         }
			break;
	}
	return false;	
}

// Define headings actions added by the plugin	 
function plugin_headings_actions_fusioninventory($type) {

   $configModules = new PluginFusioninventoryConfigModules;

	switch ($type) {
		case COMPUTER_TYPE :
			$array = array ();
         if (($configModules->isActivated('remotehttpagent')) AND (PluginFusioninventory::haveRight("remotecontrol","w"))) {
             $array[1] = "plugin_headings_fusioninventory_computerInfo";
         }
         $array[2] = "plugin_headings_fusioninventory_fusioninventoryLocks";
			return $array;
			break;

		case MONITOR_TYPE :
         $array = array ();
         $array = array (
            1 => "plugin_headings_fusioninventory_fusioninventoryLocks"
         );
      case PRINTER_TYPE :
			$array = array ();
			if (PluginFusioninventory::haveRight("snmp_printers", "r")) {
				$array[1] = "plugin_headings_fusioninventory_printerInfo";
			}
         $array[2] = "plugin_headings_fusioninventory_fusioninventoryLocks";
			return $array;
			break;

		case NETWORKING_TYPE :
			if (PluginFusioninventory::haveRight("snmp_networking", "r")) {
				$array[1] = "plugin_headings_fusioninventory_networkingInfo";
			}
         $array[2] = "plugin_headings_fusioninventory_fusioninventoryLocks";
			return $array;
			break;

		case PROFILE_TYPE :
			return array(
				1 => "plugin_headings_fusioninventory",
				);
			break;

	}
	return false;
}


function plugin_headings_fusioninventory_computerErrors($type, $ID) {
	$errors = new PluginFusioninventoryErrors;
	$errors->showForm(COMPUTER_TYPE, GLPI_ROOT . '/plugins/fusioninventory/front/errors.form.php', $_GET["ID"]);
}

function plugin_headings_fusioninventory_computerInfo($type, $ID) {
   $pfit = new PluginFusioninventoryTask;
   $pfit->RemoteStateAgent(GLPI_ROOT . '/plugins/fusioninventory/front/agents.state.php', $ID, $type, array('INVENTORY' => 1, 'NETDISCOVERY' => 1, 'SNMPQUERY' => 1, 'WAKEONLAN' => 1));
}

function plugin_headings_fusioninventory_printerInfo($type, $ID) {
	include_once(GLPI_ROOT."/inc/stat.function.php");
	$plugin_fusioninventory_printers = new PluginFusioninventoryPrinters;
	$plugin_fusioninventory_printers->showFormPrinter($ID, 
               array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/printer_info.form.php'));
	echo '<div id="overDivYFix" STYLE="visibility:hidden">fusioninventory_1</div>';
   $plugin_fusioninventory_printers->showFormPrinter_graph($ID, 
               array('target'=>GLPI_ROOT . '/plugins/fusioninventory/front/printer_info.form.php'));
}

function plugin_headings_fusioninventory_printerHistory($type, $ID) {
	$print_history = new PluginFusioninventoryPrintersHistory;
	$print_history->showForm($_GET["ID"],
               array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/printer_history.form.php'));
}

function plugin_headings_fusioninventory_printerErrors($type, $ID) {
	$errors = new PluginFusioninventoryErrors;
	$errors->showForm(PRINTER_TYPE, GLPI_ROOT . '/plugins/fusioninventory/front/errors.form.php', $_GET["ID"]);
}

function plugin_headings_fusioninventory_networkingInfo($type, $ID) {
	$snmp = new PluginFusioninventoryNetworking;
	$snmp->showForm($ID, 
           array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/switch_info.form.php'));
}

function plugin_headings_fusioninventory_networkingErrors($type, $ID) {
	$errors = new PluginFusioninventoryErrors;
	$errors->showForm(NETWORKING_TYPE, GLPI_ROOT . '/plugins/fusioninventory/front/errors.form.php', $_GET["ID"]);
}

function plugin_headings_fusioninventory_fusioninventoryLocks($type, $ID) {
	$fusioninventory_locks = new PluginFusioninventoryLock();
	$fusioninventory_locks->showForm(GLPI_ROOT . '/plugins/fusioninventory/front/lock.form.php', $type, $ID);
}

function plugin_headings_fusioninventory($type,$ID,$withtemplate=0) {
	global $CFG_GLPI;

	switch ($type) {
		case PROFILE_TYPE :
			$prof=new PluginFusioninventoryProfile;
			if (!$prof->GetfromDB($ID)) {
				PluginFusioninventory::createaccess($ID);
         }
			$prof->showForm($ID, 
              array('target'=>$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/profile.php"));
		break;
	}
}


function plugin_fusioninventory_MassiveActions($type) {
	global $LANG;
	switch ($type) {
		case NETWORKING_TYPE :
			return array (
            "plugin_fusioninventory_get_model" => $LANG['plugin_fusioninventory']["model_info"][14],
				"plugin_fusioninventory_assign_model" => $LANG['plugin_fusioninventory']["massiveaction"][1],
				"plugin_fusioninventory_assign_auth" => $LANG['plugin_fusioninventory']["massiveaction"][2],
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']["functionalities"][75]
			);
			break;

		case PRINTER_TYPE :
			return array (
            "plugin_fusioninventory_get_model" => $LANG['plugin_fusioninventory']["model_info"][14],
				"plugin_fusioninventory_assign_model" => $LANG['plugin_fusioninventory']["massiveaction"][1],
				"plugin_fusioninventory_assign_auth" => $LANG['plugin_fusioninventory']["massiveaction"][2],
            "plugin_fusioninventory_manage_locks" => $LANG['plugin_fusioninventory']["functionalities"][75]
			);
			break;

		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
			return array (
				"plugin_fusioninventory_discovery_import" => $LANG["buttons"][37]
			);
	}
	return array ();
}

function plugin_fusioninventory_MassiveActionsDisplay($type, $action) {

	global $LANG, $CFG_GLPI, $DB;
	switch ($type) {
		case NETWORKING_TYPE :
			switch ($action) {

            case "plugin_fusioninventory_get_model" :
               if(PluginFusioninventory::haveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_model" :
               if(PluginFusioninventory::haveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusioninventory_modelinfos`
                                   WHERE `itemtype`!='2'
                                         AND `itemtype`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['ID'];
                  }
                  Dropdown::show("PluginFusioninventoryModelInfos",
                                 array('name' => "snmp_model",
                                       'value' => "name",
                                       'comment' => false,
                                       'used' => $exclude_models));
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_auth" :
               if(PluginFusioninventory::haveRight("snmp_authentification","w")) {
                  PluginFusioninventorySNMP::auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock;
               $pfil->showForm($_SERVER["PHP_SELF"], NETWORKING_TYPE, '');
               break;

			}
			break;

		case PRINTER_TYPE :
			switch ($action) {

            case "plugin_fusioninventory_get_model" :
               if(PluginFusioninventory::haveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_model" :
               if(PluginFusioninventory::haveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusioninventory_modelinfos`
                                   WHERE `itemtype`!='3'
                                         AND `itemtype`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['ID'];
                  }
                  Dropdown::show("PluginFusioninventoryModelInfos",
                                 array('name' => "snmp_model",
                                       'value' => "name",
                                       'comment' => false,
                                       'used' => $exclude_models));
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_auth" :
               if(PluginFusioninventory::haveRight("snmp_authentification","w")) {
                  PluginFusioninventorySNMP::auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

            case "plugin_fusioninventory_manage_locks" :
               $pfil = new PluginFusioninventoryLock;
               $pfil->showForm($_SERVER["PHP_SELF"], NETWORKING_TYPE, '');
               break;

			}
			break;

		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
			switch ($action) {
				case "plugin_fusioninventory_discovery_import" :
               if(PluginFusioninventory::haveRight("unknowndevices","w")) {
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
					break;
			}
			break;
	}
	return "";
}

function plugin_fusioninventory_MassiveActionsProcess($data) {
	global $LANG;
	switch ($data['action']) {

      case "plugin_fusioninventory_get_model" :
         if ($data['itemtype'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginFusioninventoryModelInfos = new PluginFusioninventoryModelInfos;
                  $PluginFusioninventoryModelInfos->getrightmodel($key, NETWORKING_TYPE);
					}
				}
         } else if($data['itemtype'] == PRINTER_TYPE) {
            foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginFusioninventoryModelInfos = new PluginFusioninventoryModelInfos;
                  $PluginFusioninventoryModelInfos->getrightmodel($key, PRINTER_TYPE);
					}
				}
         }
         break;

		case "plugin_fusioninventory_assign_model" :
			if ($data['itemtype'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						PluginFusioninventoryMassiveaction::assign($key, NETWORKING_TYPE, "model", $data["snmp_model"]);
					}
				}
			} else if($data['itemtype'] == PRINTER_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						PluginFusioninventoryMassiveaction::assign($key, PRINTER_TYPE, "model", $data["snmp_model"]);
					}
				}
			}
			break;
      
		case "plugin_fusioninventory_assign_auth" :
			if ($data['itemtype'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						PluginFusioninventoryMassiveaction::assign($key, NETWORKING_TYPE, "auth", $data["plugin_fusioninventory_snmpauths_id"]);
					}
				}
			} else if($data['itemtype'] == PRINTER_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						PluginFusioninventoryMassiveaction::assign($key, PRINTER_TYPE, "auth", $data["plugin_fusioninventory_snmpauths_id"]);
					}
				}
			}
			break;

      case "plugin_fusioninventory_manage_locks" :
         if (($data['itemtype'] == NETWORKING_TYPE) OR ($data['itemtype'] == PRINTER_TYPE)) {
            foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  if (isset($data["lockfield_fusioninventory"])&&count($data["lockfield_fusioninventory"])){
                     $tab=PluginFusioninventoryLock::exportChecksToArray($data["lockfield_fusioninventory"]);
                        PluginFusioninventoryLock::setLockArray($data['type'], $key, $tab);
                  } else {
                     PluginFusioninventoryLock::setLockArray($data['type'], $key, array());
                  }
               }
            }
         }
         break;
      
		case "plugin_fusioninventory_discovery_import" :
         if(PluginFusioninventory::haveRight("unknowndevices","w")) {
            $Import = 0;
            $NoImport = 0;
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  list($Import, $NoImport) = PluginFusioninventoryDiscovery::import($key,$Import,$NoImport);
               }
            }
            addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][5]." : ".$Import);
            addMessageAfterRedirect($LANG['plugin_fusioninventory']["discovery"][9]." : ".$NoImport);
         }
			break;
	}
}

// How to display specific update fields ?
// Massive Action functions
function plugin_fusioninventory_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
	global $LINK_ID_TABLE,$LANG;
	// Table fields
	//echo $table.".".$field."<br/>";
	switch ($table.".".$field) {

		case 'glpi_plugin_fusioninventory_snmpauths.name':
			Dropdown::show("PluginFusioninventorySnmpauth",
                        array('name' => $linkfield));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_modelinfos.name':
			Dropdown::show("PluginFusioninventoryModelInfos",
                        array('name' => $linkfield,
                              'comment' => false));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_unknown_device.type' :
         $type_list = array();
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			Device::dropdownTypes('type',$linkfield,$type_list);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.ID' :
			Dropdown::show("PluginFusioninventoryAgents",
                        array('name' => $linkfield,
                              'comment' => false));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.nb_process_query' :
			Dropdown::showInteger("nb_process_query", $linkfield,1,200);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.nb_process_discovery' :
			Dropdown::showInteger("nb_process_discovery", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.logs' :
			$ArrayValues[]= $LANG["choice"][0];
			$ArrayValues[]= $LANG["choice"][1];
			$ArrayValues[]= $LANG["setup"][137];
			Dropdown::showFromArray('logs', $ArrayValues,
                                 array('value'=>$linkfield));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.core_discovery' :
			Dropdown::showInteger("core_discovery", $linkfield,1,32);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.core_query' :
			Dropdown::showInteger("core_query", $linkfield,1,32);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.threads_discovery' :
			Dropdown::showInteger("threads_discovery", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.threads_query' :
			Dropdown::showInteger("threads_query", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_discovery.plugin_fusioninventory_snmpauths_id' :
			$plugin_fusioninventory_snmp = new PluginFusioninventorySnmpauth;
			echo $plugin_fusioninventory_snmp->selectbox();
			return true;
			break;

		case 'glpi_plugin_fusioninventory_modelinfos.itemtype' :
         $type_list = array();
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			Device::dropdownTypes('type',$linkfield,$type_list);
			return true;
			break;

      case 'glpi_entities.name' :
         if (isMultiEntitiesMode()) {
            Dropdown::show("Entities",
		                     array('name' => "entities_id",
                           'value' => $_SESSION["glpiactive_entity"]));
         }
         return true;
         break;
	}
	return false;
}



function plugin_fusioninventory_addSelect($type,$ID,$num) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

			// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;
			}
			break;
		// * PRINTER List (front/printer.php)
      case PRINTER_TYPE :
         switch ($table.".".$field) {

         // ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

				// ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
         break;

		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
			switch ($table.".".$field) {

				case "glpi_networking.device" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				case "glpi_networking_ports.Networkport" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

			}
			break;

      case PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP :
         switch ($table.".".$SEARCH_OPTION[$type][$ID]["linkfield"]) {

            case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_query" :
               return "GROUP_CONCAT( DISTINCT CONCAT(gpta.name,'$$' ,gpta.ID) SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
	}
	return "";
}


function plugin_fusioninventory_forceGroupBy($type) {
    switch ($type) {

      case COMPUTER_TYPE :
         // ** FusionInventory - switch
         return "GROUP BY glpi_computers.id";
         break;

        case PRINTER_TYPE :
            // ** FusionInventory - switch
            return "GROUP BY glpi_printers.id";
            break;

    }
    return false;
}


// Search modification for plugin FusionInventory

function plugin_fusioninventory_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {


//	echo "Left Join : ".$new_table.".".$linkfield."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($new_table.".".$linkfield) {
				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON glpi_networking_ports.ID = FUSIONINVENTORY_11.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.on_device = glpi_computers.ID AND FUSIONINVENTORY_10.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";
               }
               break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $table_networking_ports = 0;
               $table_fusioninventory_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusioninventory_networking.ID") {
                     $table_fusioninventory_networking = 1;
                  }
               }
               if ($table_fusioninventory_networking == "1") {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID=FUSIONINVENTORY_12.ID ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON glpi_networking_ports.ID = FUSIONINVENTORY_21.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.on_device = glpi_computers.ID AND FUSIONINVENTORY_20.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";

               }
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networking.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networking ON (glpi_networking.ID = glpi_plugin_fusioninventory_networking.networkequipments_id) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networking AS gptn_model ON (glpi_networking.ID = gptn_model.networkequipments_id) ".
						" LEFT JOIN glpi_plugin_fusioninventory_modelinfos ON (gptn_model.plugin_fusioninventory_modelinfos_id = glpi_plugin_fusioninventory_modelinfos.ID) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networking AS gptn_auth ON glpi_networking.ID = gptn_auth.networkequipments_id ".
						" LEFT JOIN glpi_plugin_fusioninventory_snmpauths ON gptn_auth.plugin_fusioninventory_snmpauths_id = glpi_plugin_fusioninventory_snmpauths.ID ";
					break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers ON (glpi_printers.ID = glpi_plugin_fusioninventory_printers.printers_id) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers AS gptp_model ON (glpi_printers.ID = gptp_model.printers_id) ".
						" LEFT JOIN glpi_plugin_fusioninventory_modelinfos ON (gptp_model.plugin_fusioninventory_modelinfos_id = glpi_plugin_fusioninventory_modelinfos.ID) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.ID" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers AS gptp_auth ON glpi_printers.ID = gptp_auth.printers_id ".
						" LEFT JOIN glpi_plugin_fusioninventory_snmpauths ON gptp_auth.plugin_fusioninventory_snmpauths_id = glpi_plugin_fusioninventory_snmpauths.ID ";
					break;

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON glpi_networking_ports.ID = FUSIONINVENTORY_11.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_10 ON (glpi_printers.ID = FUSIONINVENTORY_10.on_device AND FUSIONINVENTORY_10.itemtype='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";
               }
               break;

               // ** FusionInventory - switch port
               case "glpi_plugin_fusioninventory_networking_ports.ID" :
                  $table_networking_ports = 0;
                  $table_fusioninventory_networking = 0;
                  foreach ($already_link_tables AS $num=>$tmp_table) {
                     if ($tmp_table == "glpi_networking_ports.") {
                        $table_networking_ports = 1;
                     }
                     if ($tmp_table == "glpi_plugin_fusioninventory_networking.ID") {
                        $table_fusioninventory_networking = 1;
                     }
                  }
                  if ($table_fusioninventory_networking == "1") {
                     return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID=FUSIONINVENTORY_12.ID ";
                  } else if ($table_networking_ports == "1") {
                     return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON glpi_networking_ports.ID = FUSIONINVENTORY_21.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_21.end2 ".
                        " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
                  } else {
                     return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.on_device = glpi_computers.ID AND FUSIONINVENTORY_20.itemtype='".PRINTER_TYPE."') ".
                      " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end2 ".
                        " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
                  }
                  break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON glpi_networking_ports.ID = FUSIONINVENTORY_11.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_10 ON (glpi_printers.ID = FUSIONINVENTORY_10.on_device AND FUSIONINVENTORY_10.itemtype='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.ID = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.ID = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.ID THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networking AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.on_device=FUSIONINVENTORY_13.ID";
               }
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $table_networking_ports = 0;
               $table_fusioninventory_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusioninventory_networking.ID") {
                     $table_fusioninventory_networking = 1;
                  }
               }
               if ($table_fusioninventory_networking == "1") {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID=FUSIONINVENTORY_12.ID ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON glpi_networking_ports.ID = FUSIONINVENTORY_21.end1 OR glpi_networking_ports.ID = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networking_ports.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.on_device = glpi_computers.ID AND FUSIONINVENTORY_20.itemtype='".PRINTER_TYPE."') ".
                   " LEFT JOIN glpi_networking_wire AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.ID = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.ID = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.ID THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               }
               break;

			}
			break;


		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2 :
			switch ($new_table.".".$linkfield) {

				// ** Location of switch
				case "glpi_dropdown_locations.networkports_id" :
					return " LEFT JOIN glpi_networking_ports ON (glpi_plugin_fusioninventory_networking_ports.networkports_id = glpi_networking_ports.ID) ".
						" LEFT JOIN glpi_networking ON glpi_networking_ports.on_device = glpi_networking.ID".
						" LEFT JOIN glpi_dropdown_locations ON glpi_dropdown_locations.ID = glpi_networking.location";
					break;

			}
			break;

		// * range IP list (plugins/fusioninventory/front/rangeip.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP :
			switch ($new_table.".".$linkfield) {

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_discovery" :
					return " LEFT JOIN glpi_plugin_fusioninventory_agents ON (glpi_plugin_fusioninventory_agents.ID = glpi_plugin_fusioninventory_rangeip.plugin_fusioninventory_agents_id_discovery) ";
					break;

            case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_query" :
               return " LEFT JOIN glpi_plugin_fusioninventory_agents AS gpta ON (glpi_plugin_fusioninventory_rangeip.plugin_fusioninventory_agents_id_query = gpta.ID) ";
               break;
            

			}
			break;

      // * ports updates list (report/switch_ports.history.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_HISTORY :
         return " LEFT JOIN `glpi_networking_ports` ON ( `glpi_networking_ports`.`ID` = `glpi_plugin_fusioninventory_snmphistories`.`networkports_id` ) ";
			break;
	}
	return "";
}



function plugin_fusioninventory_addOrderBy($type,$ID,$order,$key=0) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "ORDER BY : ".$table.".".$field;

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
					return " ORDER BY FUSIONINVENTORY_12.on_device $order ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
					return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networking.networkequipments_id" :
					return " ORDER BY glpi_plugin_fusioninventory_networking.last_fusioninventory_update $order ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					return " ORDER BY glpi_plugin_fusioninventory_modelinfos.name $order ";
					break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.printers_id" :
					return " ORDER BY glpi_plugin_fusioninventory_printers.last_fusioninventory_update $order ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					return " ORDER BY glpi_plugin_fusioninventory_modelinfos.name $order ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.ID" :
					return " ORDER BY glpi_plugin_fusioninventory_snmpauths.name $order ";
					break;

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               return " ORDER BY FUSIONINVENTORY_12.on_device $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               return " ORDER BY FUSIONINVENTORY_12.on_device $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

			}
			break;

		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2 :
			switch ($table.".".$field) {

				// ** Location of switch
				case "glpi_dropdown_locations.ID" :
					return " ORDER BY glpi_dropdown_locations.name $order ";
					break;

			}
			break;

		// * range IP list (plugins/fusioninventory/front/rangeip.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP :
			switch ($table.".".$field) {
			
				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.ID" :
					return " ORDER BY glpi_plugin_fusioninventory_agents.name $order ";
					break;

			}
			break;

		// * Detail of ports history (plugins/fusioninventory/report/switch_ports.history.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_HISTORY :
			switch ($table.".".$field) {

				// ** Display switch and Port
				case "glpi_plugin_fusioninventory_snmphistories.ID" :
					return " ORDER BY glpi_plugin_fusioninventory_snmphistories.ID $order ";
					break;
				case "glpi_networking_ports.ID" :
					return " ORDER BY glpi_networking.name,glpi_networking_ports.name $order ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_snmphistories.Field" :
					return " ORDER BY glpi_plugin_fusioninventory_snmphistories.Field $order ";
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_fusioninventory_snmphistories.old_value" :
					return " ORDER BY glpi_plugin_fusioninventory_snmphistories.old_value $order ";
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_fusioninventory_snmphistories.new_value" :
					return " ORDER BY glpi_plugin_fusioninventory_snmphistories.new_value $order ";
					break;

				case "glpi_plugin_fusioninventory_snmphistories.date_mod" :
				return " ORDER BY glpi_plugin_fusioninventory_snmphistories.date_mod $order ";
						break;

			}
	}
	return "";
}



function plugin_fusioninventory_addWhere($link,$nott,$type,$ID,$val) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "add where : ".$table.".".$field."<br/>";
	$SEARCH=makeTextSearch($val,$nott);

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networking.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_12.on_device IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_12.on_device IS NOT NULL";
					}
					return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_22.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
					}
					return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.".".$field) {

         // ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networking.networkequipments_id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NOT NULL";
					}
					return $link." ($table.last_fusioninventory_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_networking.plugin_fusioninventory_snmpauths_id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_fusioninventory_snmpauths.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_fusioninventory_snmpauths.name IS NOT NULL";
					}
					return $link." (glpi_plugin_fusioninventory_snmpauths.name  LIKE '%".$val."%' $ADD ) ";
					break;

            // ** FusionInventory - CPU
            case "glpi_plugin_fusioninventory_networking.cpu":

               break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.printers_id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NOT NULL";
					}
					return $link." ($table.last_fusioninventory_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_modelinfos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_snmpauths.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.on_device IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.on_device IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
               break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networking.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.on_device IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.on_device IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networking_ports.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_22.name IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_22.name  LIKE '%".$val."%' $ADD ) ";
               break;
			}
			break;

		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2 :
			switch ($table.".".$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_fusioninventory_networking_ports.ID" :
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_fusioninventory_networking_ports.networkports_id" :
					break;

				// ** Location of switch
				case "glpi_dropdown_locations.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_networking.location IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_networking.location IS NOT NULL";
					}
					if ($val == "0") {
						return $link." (glpi_networking.location >= -1 ) ";
               }
					return $link." (glpi_networking.location = '".$val."' $ADD ) ";
					break;

				case "glpi_plugin_fusioninventory_networking_ports.lastup" :
					$ADD = "";
					//$val = str_replace("&lt;",">",$val);
					//$val = str_replace("\\","",$val);
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NOT NULL";
					}
					return $link." ($table.$field $val $ADD ) ";
					break;
			}
			break;

		// * range IP list (plugins/fusioninventory/front/rangeip.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP :
			switch ($table.".".$field) {

				// ** Name of range IP and link to form
				case "glpi_plugin_fusioninventory_rangeip.name" :
					break;

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}

         switch ($table.".".$SEARCH_OPTION[$type][$ID]["linkfield"]) {

            case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_query" :
               $ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
               return $link." (gpta.name  LIKE '%".$val."%' $ADD ) ";
               break;

         }

			break;

		// * Detail of ports history (plugins/fusioninventory/report/switch_ports.history.php)
		case PLUGIN_FUSIONINVENTORY_SNMP_HISTORY :
			switch ($table.".".$field) {

				// ** Display switch and Port
				case "glpi_networking_ports.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.ID IS NULL ";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.ID IS NOT NULL ";
					}
					return $link." ($table.ID = '".$val."' $ADD ) ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_snmphistories.Field" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NULL ";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NOT NULL ";
					}
					if (!empty($val)) {
                  include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/snmp.mapping.constant.php");
						$val = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$val]['field'];
               }
					return $link." ($table.$field = '".addslashes($val)."' $ADD ) ";
					break;

			}
	}
	return "";
}

function plugin_pre_item_purge_fusioninventory($parm) {
	global $DB;

	if (isset($parm["_item_type_"])) {
		switch ($parm["_item_type_"]) {
			case NETWORKING_TYPE :
				// Delete all ports
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking`
                             WHERE `networkequipments_id`='".$parm["ID"]."';";
				$DB->query($query_delete);

				$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ports`.`ID`
                             FROM `glpi_plugin_fusioninventory_networking_ports`
                                  LEFT JOIN `glpi_networking_ports`
                                            ON `glpi_networking_ports`.`ID` = `networkports_id`
                             WHERE `on_device`='".$parm["ID"]."'
                                   AND `itemtype`='".NETWORKING_TYPE."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
                                WHERE `ID`='".$data["ID"]."';";
					$DB->query($query_delete);
				}

				$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ifaddr`.`ID`
                             FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                                  LEFT JOIN `glpi_networking`
                                            ON `glpi_networking`.`ID` = `networkequipments_id`
                             WHERE `networkequipments_id`='".$parm["ID"]."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                                WHERE `ID`='".$data["ID"]."';";
					$DB->query($query_delete);
				}
            break;

			case PRINTER_TYPE :
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printers`
                             WHERE `printers_id`='".$parm["ID"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printers_cartridges`
                             WHERE `printers_id`='".$parm["ID"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printers_history`
                             WHERE `printers_id`='".$parm["ID"]."';";
				$DB->query($query_delete);
            break;

         case PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN :
            // Delete ports and connections if exists
            $np=new Networkport;
            $nn = new NetworkPort_NetworkPort();
            $query = "SELECT `ID`
                      FROM `glpi_networking_ports`
                      WHERE `on_device` = '".$parm["ID"]."'
                            AND `itemtype` = '".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."';";
            $result = $DB->query($query);
            while ($data = $DB->fetch_array($result)) {
               if ($nn->getFromDBForNetworkPort($data['ID'])) {
                  $nn->delete($data);
               }
               $np->delete(array("ID"=>$data["ID"]));
            }
            break;

         case COMPUTER_TYPE :
            // Delete link between computer and agent fusion
            $query = "UPDATE `glpi_plugin_fusioninventory_agents`
                        SET `items_id` = '0'
                           AND `itemtype` = '0'
                        WHERE `items_id` = '".$parm["ID"]."'
                           AND `itemtype` = '1' ";
            $DB->query($query);
            break;

		}
   }
	return $parm;
}



function plugin_pre_item_delete_fusioninventory($parm) {
	global $DB;

	if (isset($parm["_item_type_"])) {
		switch ($parm["_item_type_"]) {

         case NETWORKING_PORT_TYPE :
            	$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
                  WHERE `networkports_id`='".$parm["ID"]."';";
					$DB->query($query_delete);
            break;

		}
   }
	return $parm;
}



/**
 * Hook after updates
 *
 * @param $parm
 * @return nothing
 *
**/
function plugin_item_update_fusioninventory($parm) {
   if (isset($_SESSION["glpiID"]) AND $_SESSION["glpiID"]!='') { // manual task
      $plugin = new Plugin;
      if ($plugin->isActivated('fusioninventory')) {
         // lock fields which have been updated
         $type=$parm['type'];
         $ID=$parm['ID'];
         $fieldsToLock=$parm['updates'];
         $lockables = PluginFusioninventoryLockable::getLockableFields('', $type);
         $fieldsToLock = array_intersect($fieldsToLock, $lockables); // do not lock unlockable fields
         PluginFusioninventoryLock::addLocks($type, $ID, $fieldsToLock);
      }
   }
}


function plugin_item_add_fusioninventory($parm) {
	global $DB;

	if (isset($parm["type"])) {
		switch ($parm["type"]) {

         case NETWORKING_PORT_TYPE :
            // Verify when add networking port on object (not unknown device) if port
            // of an unknown device exist.
            if ($parm["input"]["itemtype"] != PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN) {
               // Search in DB
               $np = new Netport;
               $nw = new Netwire;
               $pfiud = new PluginFusionInventoryUnknownDevice;
               $a_ports = $np->find("`ifmac`='".$parm["input"]["ifmac"]."' AND `itemtype`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."' ");
               if (count($a_ports) == "1") {
                  foreach ($a_ports as $port_infos) {
                     // Get wire
                     $opposite_ID = $nw->getOppositeContact($port_infos['id']);
                     if (isset($opposite_ID)) {
                        // Modify wire
                        removeConnector($port_infos['id']);
                        makeConnector($parm['id'], $opposite_ID);
                     }
                     // Delete port
                     $np->deleteFromDB($port_infos['id']);
                     // Delete unknown device (if it has no port)
                     if (count($np->find("`items_id`='".$port_infos['items_id']."' AND `itemtype`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."' ")) == "0") {
                        $pfiud->deleteFromDB($port_infos['items_id']);
                     }
                  }
               }
            }
            break;

      }
   }
}

?>
