<?php

/*
 * @version $Id: connection.function.php 6975 2008-06-13 15:43:18Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------
TRACKER_12.on_device
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

function plugin_tracker_getSearchOption() {
	global $LANG;
	$sopt = array ();

	$config = new PluginTrackerConfig;

	// Part header
	$sopt[PLUGIN_TRACKER_ERROR_TYPE]['common'] = $LANG['plugin_tracker']["errors"][0];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['field'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['linkfield'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['name'] = $LANG['plugin_tracker']["errors"][1];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][30]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['field'] = 'device_type';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['linkfield'] = 'device_type';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][3]['name'] = $LANG["common"][1];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['field'] = 'device_id';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['linkfield'] = 'device_id';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][4]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['field'] = 'description';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['linkfield'] = 'description';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['name'] = $LANG['plugin_tracker']["errors"][2];
  $sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['datatype']='text';
  
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['field'] = 'first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['linkfield'] = 'first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['name'] = $LANG['plugin_tracker']["errors"][3];
  $sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['datatype']='datetime';
  
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['field'] = 'last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['linkfield'] = 'last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['name'] = $LANG['plugin_tracker']["errors"][4];
  $sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['datatype']='datetime';
  
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['field'] = 'completename';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_TRACKER_MODEL]['common'] = $LANG['plugin_tracker']["profile"][19];

	$sopt[PLUGIN_TRACKER_MODEL][1]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MODEL][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_MODEL][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_TRACKER_MODEL][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_TRACKER_MODEL][30]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MODEL][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_MODEL][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_MODEL][3]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][3]['field'] = 'device_type';
	$sopt[PLUGIN_TRACKER_MODEL][3]['linkfield'] = 'device_type';
	$sopt[PLUGIN_TRACKER_MODEL][3]['name'] = $LANG["common"][17];

	$sopt[PLUGIN_TRACKER_MODEL][5]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][5]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MODEL][5]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_MODEL][5]['name'] = $LANG["buttons"][31];

	$sopt[PLUGIN_TRACKER_MODEL][6]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][6]['field'] = 'activation';
	$sopt[PLUGIN_TRACKER_MODEL][6]['linkfield'] = 'activation';
	$sopt[PLUGIN_TRACKER_MODEL][6]['name'] = $LANG['plugin_tracker']["model_info"][11];
	$sopt[PLUGIN_TRACKER_MODEL][6]['datatype']='bool';

	$sopt[PLUGIN_TRACKER_MODEL][7]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][7]['field'] = 'discovery_key';
	$sopt[PLUGIN_TRACKER_MODEL][7]['linkfield'] = 'discovery_key';
	$sopt[PLUGIN_TRACKER_MODEL][7]['name'] = $LANG['plugin_tracker']["model_info"][12];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH]['common'] = $LANG['plugin_tracker']["profile"][22];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][30]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][30]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['table'] = 'glpi_dropdown_plugin_tracker_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['linkfield'] = 'FK_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['name'] = $LANG['plugin_tracker']["model_info"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['field'] = 'community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['linkfield'] = 'community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['name'] = $LANG['plugin_tracker']["snmpauth"][1];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['field'] = 'sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['linkfield'] = 'sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['name'] = $LANG['plugin_tracker']["snmpauth"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['table'] = 'glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['linkfield'] = 'auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['name'] = $LANG['plugin_tracker']["snmpauth"][4];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['field'] = 'auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['linkfield'] = 'auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['name'] = $LANG['plugin_tracker']["snmpauth"][5];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['table'] = 'glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['linkfield'] = 'priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['name'] = $LANG['plugin_tracker']["snmpauth"][6];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['field'] = 'priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['linkfield'] = 'priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['name'] = $LANG['plugin_tracker']["snmpauth"][7];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN]['common'] = $LANG['plugin_tracker']["menu"][4];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['datatype']='itemlink';

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['field'] = 'dnsname';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['linkfield'] = 'dnsname';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['name'] = $LANG['plugin_tracker']["unknown"][0];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['field'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['name'] = $LANG["common"][26];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['field'] = 'serial';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['linkfield'] = 'serial';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['name'] = $LANG['common'][19];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['field'] = 'otherserial';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['linkfield'] = 'otherserial';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['name'] = $LANG['common'][20];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][7]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][7]['field'] = 'contact';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][7]['linkfield'] = 'contact';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][7]['name'] = $LANG['common'][18];
 
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][8]['table'] = 'glpi_dropdown_domain';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][8]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][8]['linkfield'] = 'domain';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][8]['name'] = $LANG["setup"][89];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][9]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][9]['field'] = 'comments';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][9]['linkfield'] = 'comments';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][9]['name'] = $LANG['common'][25];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][10]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][10]['field'] = 'type';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][10]['linkfield'] = 'type';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][10]['name'] = $LANG['common'][17];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][11]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][11]['field'] = 'snmp';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][11]['linkfield'] = 'snmp';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][11]['name'] = $LANG['plugin_tracker']["functionalities"][3];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][11]['datatype']='bool';
   
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][12]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][12]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][12]['linkfield'] = 'FK_model_infos';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][12]['name'] = $LANG['plugin_tracker']["model_info"][4];

   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][13]['table'] = 'glpi_plugin_tracker_snmp_connection';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][13]['field'] = 'name';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][13]['linkfield'] = 'FK_snmp_connection';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][13]['name'] = $LANG['plugin_tracker']["model_info"][3];

   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][14]['table'] = 'glpi_networking_ports';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][14]['field'] = 'ifaddr';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][14]['linkfield'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][14]['name'] = $LANG["networking"][14];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][14]['forcegroupby']='1';

   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][15]['table'] = 'glpi_networking_ports';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][15]['field'] = 'ifmac';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][15]['linkfield'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][15]['name'] = $LANG["networking"][15];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][15]['forcegroupby']='1';

   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][16]['table'] = 'glpi_plugin_tracker_networking';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][16]['field'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][16]['linkfield'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][16]['name'] = $LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][52];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][16]['forcegroupby'] = '1';

   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][17]['table'] = 'glpi_plugin_tracker_networking_ports';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][17]['field'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][17]['linkfield'] = 'ID';
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][17]['name'] = $LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][46];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][17]['forcegroupby'] = '1';

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][18]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][18]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][18]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][18]['name'] = $LANG['plugin_tracker']["unknown"][1];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][18]['forcegroupby']='1';

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][19]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][19]['field'] = 'accepted';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][19]['linkfield'] = 'accepted';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][19]['name'] = $LANG['plugin_tracker']["unknown"][2];
   $sopt[PLUGIN_TRACKER_MAC_UNKNOWN][19]['datatype']='bool';

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS]['common'] = $LANG['plugin_tracker']["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][2]['name'] = $LANG['plugin_tracker']["snmp"][42];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][3]['name'] = $LANG['plugin_tracker']["snmp"][43];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][4]['name'] = $LANG['plugin_tracker']["snmp"][44];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][5]['name'] = $LANG['plugin_tracker']["snmp"][45];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][6]['name'] = $LANG['plugin_tracker']["snmp"][46];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][7]['name'] = $LANG['plugin_tracker']["snmp"][47];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][8]['name'] = $LANG['plugin_tracker']["snmp"][48];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][9]['name'] = $LANG['plugin_tracker']["snmp"][49];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][10]['name'] = $LANG['plugin_tracker']["snmp"][51];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][11]['name'] = $LANG['plugin_tracker']["mapping"][115];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][12]['name'] = $LANG["networking"][17];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][13]['name'] = $LANG['plugin_tracker']["snmp"][50];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][14]['name'] = $LANG["networking"][56];

   $sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][15]['name'] = $LANG['plugin_tracker']["snmp"][41];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS]['common'] = $LANG['plugin_tracker']["profile"][26];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][30]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['field'] = 'core_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['linkfield'] = 'core_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['name'] = $LANG['plugin_tracker']["agents"][11];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['field'] = 'threads_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['linkfield'] = 'threads_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['name'] = $LANG['plugin_tracker']["agents"][3];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['field'] = 'core_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['linkfield'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['name'] = $LANG['plugin_tracker']["agents"][10];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['field'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['linkfield'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['name'] = $LANG['plugin_tracker']["agents"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['field'] = 'fragment';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['linkfield'] = 'fragment';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['name'] = $LANG['plugin_tracker']["agents"][8];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['field'] = 'last_agent_update';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['name'] = $LANG['plugin_tracker']["agents"][4];
  $sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['datatype']='datetime';
  
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['field'] = 'tracker_agent_version';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['name'] = $LANG['plugin_tracker']["agents"][5];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['field'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['linkfield'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['name'] = $LANG['plugin_tracker']["agents"][6];
  $sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['datatype']='bool';
  
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['field'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['linkfield'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['name'] = $LANG["Menu"][30];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['name'] = $LANG['plugin_tracker']["agents"][7];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP]['common'] = $LANG['plugin_tracker']["profile"][25];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['name'] = $LANG["common"][16];
  $sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['datatype']='itemlink';
  
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['field'] = 'ifaddr_start';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['linkfield'] = 'ifaddr_start';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['name'] = $LANG['plugin_tracker']["rangeip"][0];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['field'] = 'ifaddr_end';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['linkfield'] = 'ifaddr_end';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['name'] = $LANG['plugin_tracker']["rangeip"][1];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][30]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['linkfield'] = 'FK_tracker_agents_discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['name'] = $LANG['plugin_tracker']["agents"][12];
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['datatype']='itemlink';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['itemlink_type']=PLUGIN_TRACKER_SNMP_AGENTS;
  $sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['forcegroupby']='1';
  
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['field'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['linkfield'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['name'] = $LANG['plugin_tracker']["discovery"][3];
  $sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['datatype']='bool';
  
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['field'] = 'query';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['linkfield'] = 'query';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['name'] = $LANG['plugin_tracker']["rangeip"][3];
  $sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['datatype']='bool';
  
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['name'] = $LANG['plugin_tracker']["agents"][13];
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['datatype']='itemlink';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['itemlink_type']=PLUGIN_TRACKER_SNMP_AGENTS;
   $sopt[PLUGIN_TRACKER_SNMP_RANGEIP][9]['forcegroupby']='1';

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY]['common'] = $LANG['plugin_tracker']["title"][2];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][30]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][30]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][2]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][2]['linkfield'] = 'FK_ports';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][2]['name'] = $LANG["setup"][175];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][3]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][3]['field'] = 'Field';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][3]['linkfield'] = 'Field';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][3]['name'] = $LANG["event"][18];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][4]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][4]['field'] = 'old_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][4]['linkfield'] = 'old_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][4]['name'] = $LANG['plugin_tracker']["history"][0];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['field'] = 'new_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['linkfield'] = 'new_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['name'] = $LANG['plugin_tracker']["history"][1];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['field'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['linkfield'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['name'] = $LANG["common"][27];
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['datatype']='datetime';


	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2]['common'] = $LANG['plugin_tracker']["profile"][28];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][30]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][30]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][30]['linkfield'] = '';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][30]['name'] = $LANG["reports"][52];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['field'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['linkfield'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['name'] = $LANG["setup"][175];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['table'] = 'glpi_dropdown_locations';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['linkfield'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['name'] = $LANG["common"][15];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['field'] = 'lastup';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['linkfield'] = 'lastup';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['name'] = $LANG["login"][0];


	$sopt[NETWORKING_TYPE][5190]['table']='glpi_plugin_tracker_model_infos';
	$sopt[NETWORKING_TYPE][5190]['field']='ID';
	$sopt[NETWORKING_TYPE][5190]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5190]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[NETWORKING_TYPE][5191]['table'] = 'glpi_plugin_tracker_networking';
		$sopt[NETWORKING_TYPE][5191]['field'] = 'FK_snmp_connection';
		$sopt[NETWORKING_TYPE][5191]['linkfield'] = 'ID';
		$sopt[NETWORKING_TYPE][5191]['name'] = $LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][20];
	} else {
		$sopt[NETWORKING_TYPE][5191]['table']='glpi_plugin_tracker_snmp_connection';
		$sopt[NETWORKING_TYPE][5191]['field']='name';
		$sopt[NETWORKING_TYPE][5191]['linkfield']='ID';
		$sopt[NETWORKING_TYPE][5191]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][20];
	}

	$sopt[NETWORKING_TYPE][5194]['table']='glpi_plugin_tracker_networking';
	$sopt[NETWORKING_TYPE][5194]['field']='FK_networking';
	$sopt[NETWORKING_TYPE][5194]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5194]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["snmp"][53];

	$sopt[NETWORKING_TYPE][5195]['table']='glpi_plugin_tracker_networking';
	$sopt[NETWORKING_TYPE][5195]['field']='cpu';
	$sopt[NETWORKING_TYPE][5195]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5195]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["snmp"][13];


	$sopt[PRINTER_TYPE][5190]['table']='glpi_plugin_tracker_model_infos';
	$sopt[PRINTER_TYPE][5190]['field']='ID';
	$sopt[PRINTER_TYPE][5190]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5190]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[PRINTER_TYPE][5191]['table'] = 'glpi_plugin_tracker_printers';
		$sopt[PRINTER_TYPE][5191]['field'] = 'FK_snmp_connection';
		$sopt[PRINTER_TYPE][5191]['linkfield'] = 'ID';
		$sopt[PRINTER_TYPE][5191]['name'] = $LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][20];
	} else {
		$sopt[PRINTER_TYPE][5191]['table']='glpi_plugin_tracker_snmp_connection';
		$sopt[PRINTER_TYPE][5191]['field']='ID';
		$sopt[PRINTER_TYPE][5191]['linkfield']='ID';
		$sopt[PRINTER_TYPE][5191]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["profile"][20];
	}

	$sopt[PRINTER_TYPE][5194]['table']='glpi_plugin_tracker_printers';
	$sopt[PRINTER_TYPE][5194]['field']='FK_printers';
	$sopt[PRINTER_TYPE][5194]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5194]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG['plugin_tracker']["snmp"][53];

	$sopt[PRINTER_TYPE][5196]['table']='glpi_plugin_tracker_networking';
	$sopt[PRINTER_TYPE][5196]['field']='ID';
	$sopt[PRINTER_TYPE][5196]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5196]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][52];
	$sopt[PRINTER_TYPE][5196]['forcegroupby']='1';

	$sopt[PRINTER_TYPE][5197]['table']='glpi_plugin_tracker_networking_ports';
	$sopt[PRINTER_TYPE][5197]['field']='ID';
	$sopt[PRINTER_TYPE][5197]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5197]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][46];
	$sopt[PRINTER_TYPE][5197]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5192]['table']='glpi_plugin_tracker_networking';
	$sopt[COMPUTER_TYPE][5192]['field']='ID';
	$sopt[COMPUTER_TYPE][5192]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5192]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][52];
	$sopt[COMPUTER_TYPE][5192]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5193]['table']='glpi_plugin_tracker_networking_ports';
	$sopt[COMPUTER_TYPE][5193]['field']='ID';
	$sopt[COMPUTER_TYPE][5193]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5193]['name']=$LANG['plugin_tracker']["title"][0]." - ".$LANG["reports"][46];
	$sopt[COMPUTER_TYPE][5193]['forcegroupby']='1';



	$sopt[PLUGIN_TRACKER_TASK]['common'] = $LANG['plugin_tracker']["task"][0];

	$sopt[PLUGIN_TRACKER_TASK][1]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][1]['field'] = 'id';
	$sopt[PLUGIN_TRACKER_TASK][1]['linkfield'] = 'id';
	$sopt[PLUGIN_TRACKER_TASK][1]['name'] = $LANG["common"][16];
   $sopt[PLUGIN_TRACKER_TASK][1]['datatype']='itemlink';

	$sopt[PLUGIN_TRACKER_TASK][2]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][2]['field'] = 'date';
	$sopt[PLUGIN_TRACKER_TASK][2]['linkfield'] = 'date';
	$sopt[PLUGIN_TRACKER_TASK][2]['name'] = $LANG["common"][27];
   $sopt[PLUGIN_TRACKER_TASK][2]['datatype']='datetime';

 	$sopt[PLUGIN_TRACKER_TASK][3]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_TASK][3]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_TASK][3]['linkfield'] = 'agent_id';
	$sopt[PLUGIN_TRACKER_TASK][3]['name'] = $LANG['plugin_tracker']["agents"][13];
	$sopt[PLUGIN_TRACKER_TASK][3]['datatype']='itemlink';
	$sopt[PLUGIN_TRACKER_TASK][3]['itemlink_type']=PLUGIN_TRACKER_SNMP_AGENTS;
   $sopt[PLUGIN_TRACKER_TASK][3]['forcegroupby']='1';

	$sopt[PLUGIN_TRACKER_TASK][4]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][4]['field'] = 'action';
	$sopt[PLUGIN_TRACKER_TASK][4]['linkfield'] = 'action';
//	$sopt[PLUGIN_TRACKER_TASK][4]['name'] = $LANG["common"][27];

	$sopt[PLUGIN_TRACKER_TASK][5]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][5]['field'] = 'param';
	$sopt[PLUGIN_TRACKER_TASK][5]['linkfield'] = 'param';
//	$sopt[PLUGIN_TRACKER_TASK][5]['name'] = $LANG["common"][27];
   
	$sopt[PLUGIN_TRACKER_TASK][6]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][6]['field'] = 'device_type';
	$sopt[PLUGIN_TRACKER_TASK][6]['linkfield'] = 'device_type';
	$sopt[PLUGIN_TRACKER_TASK][6]['name'] = $LANG["common"][1];
 
	$sopt[PLUGIN_TRACKER_TASK][7]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][7]['field'] = 'on_device';
	$sopt[PLUGIN_TRACKER_TASK][7]['linkfield'] = 'on_device';
//	$sopt[PLUGIN_TRACKER_TASK][7]['name'] = $LANG["common"][27];

	$sopt[PLUGIN_TRACKER_TASK][8]['table'] = 'glpi_plugin_tracker_task';
	$sopt[PLUGIN_TRACKER_TASK][8]['field'] = 'single';
	$sopt[PLUGIN_TRACKER_TASK][8]['linkfield'] = 'single';
//	$sopt[PLUGIN_TRACKER_TASK][8]['name'] = $LANG["common"][27];

	return $sopt;
}


function plugin_tracker_giveItem($type,$ID,$data,$num) {
	global $CFG_GLPI, $DB, $INFOFORM_PAGES, $LINK_ID_TABLE,$LANG,$SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "GiveItem : ".$field."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.'.'.$field) {

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					$netport = new Netport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $netport->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$vartmp."\">";
                  $out .=  $netport->device_name;
                  $out .= $vartmp;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
					return "<center>".$out."</center>";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Netport;
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

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_networking.FK_networking" :
					$query = "SELECT *
                         FROM `glpi_plugin_tracker_networking`
                         WHERE `FK_networking` = '".$data["ID"]."';";
					if ($result = $DB->query($query)) {
						$data2=$DB->fetch_array($result);
               }

					$last_date = "";
					if (isset($data2["last_tracker_update"])) {
						$last_date = $data2["last_tracker_update"];
               }
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$plugin_tracker_snmp = new PluginTrackerSNMP;
					$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_model_infos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.name" :
					$plugin_tracker_snmp = new PluginTrackerSNMPAuth;
					$FK_auth_DB = $plugin_tracker_snmp->GetSNMPAuth($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.snmp_auth.form.php?ID=" . $FK_auth_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_snmp_connection", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.'.'.$field) {

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               $netport = new Netport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $netport->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$vartmp."\">";
                  $out .=  $netport->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Netport;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_printers.FK_printers" :
					$query = "SELECT *
                         FROM `glpi_plugin_tracker_printers`
                         WHERE `FK_printers` = '".$data["ID"]."';";
					if ($result = $DB->query($query)) {
						$data2=$DB->fetch_array($result);
               }

					$last_date = "";
					if (isset($data2["last_tracker_update"])) {
						$last_date = $data2["last_tracker_update"];
               }
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$plugin_tracker_snmp = new PluginTrackerSNMP;
					$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_model_infos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					$plugin_tracker_snmp = new PluginTrackerSNMPAuth;
					$FK_auth_DB = $plugin_tracker_snmp->GetSNMPAuth($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.snmp_auth.form.php?ID=" . $FK_auth_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_snmp_connection", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * Model List (plugins/tracker/front/plugin_tracker.models.php)
		case PLUGIN_TRACKER_MODEL :
			switch ($table.'.'.$field) {

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_tracker_model_infos.device_type" :
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
				case "glpi_plugin_tracker_model_infos.ID" :
					$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.models.export.php' target='_blank'>
						<input type='hidden' name='model' value='" . $data["ID"] . "' />
						<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
						</form></div>";
					return "<center>".$out."</center>";
					break;

			}
			break;


		// * Authentification List (plugins/tracker/front/plugin_tracker.snmp_auth.php)
		case PLUGIN_TRACKER_SNMP_AUTH :
			switch ($table.'.'.$field) {

				// ** Hidden auth passphrase (SNMP v3)
				case "glpi_plugin_tracker_snmp_connection.auth_passphrase" :
					if (empty($data["ITEM_$num"])) {
						$out = "";
               } else {
						$out = "********";
               }
					return $out;
					break;

				// ** Hidden priv passphrase (SNMP v3)
				case "glpi_plugin_tracker_snmp_connection.priv_passphrase" :
					if (empty($data["ITEM_$num"])) {
						$out = "";
               } else {
						$out = "********";
               }
					return $out;
					break;
			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.'.'.$field) {

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               $netport = new Netport;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $netport->getDeviceData($vartmp,PLUGIN_TRACKER_MAC_UNKNOWN);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[PLUGIN_TRACKER_MAC_UNKNOWN]."?ID=".$vartmp."\">";
                  $out .=  $netport->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               $out = '';
               include_once(GLPI_ROOT."/inc/networking.class.php");
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new Netport;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

			}
			break;

		// *
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS :
			switch ($table.'.'.$field) {

			}
			break;

		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($table.'.'.$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_tracker_networking_ports.ID" :
					$query = "SELECT `glpi_networking`.`name` AS `name`, `glpi_networking`.`ID` AS `ID`
                         FROM `glpi_networking`
                              LEFT JOIN `glpi_networking_ports`
                                        ON `on_device` = `glpi_networking`.`ID`
                              LEFT JOIN `glpi_plugin_tracker_networking_ports`
                                        ON `glpi_networking_ports`.`ID`=`FK_networking_ports`
                         WHERE `glpi_plugin_tracker_networking_ports`.`ID`='".$data["ITEM_$num"]."'
                         LIMIT 0,1;";
					$result = $DB->query($query);
					$data2 = $DB->fetch_assoc($result);
					$out = "<a href='".GLPI_ROOT."/front/networking.form.php?ID=".$data2["ID"]."'>".$data2["name"]."</a>";
				return "<center>".$out."</center>";
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_tracker_networking_ports.FK_networking_ports" :
					$netport=new Netport;
					$netport->getFromDB($data["ITEM_$num"]);
               $name = "";
               if (isset($netport->fields["name"])) {
                  $name = $netport->fields["name"];
               }
					$out = "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$name."</a>";
					return "<center>".$out."</center>";
					break;

				// ** Location of switch
				case "glpi_dropdown_locations.ID" :
					$out = getDropdownName("glpi_dropdown_locations",$data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * Tracker Agents list (plugins/tracker/front/plugin_tracker.agents.php)
		case PLUGIN_TRACKER_SNMP_AGENTS :
			switch ($table.'.'.$field) {

				// ** Display log activation / level
				case "glpi_plugin_tracker_agents.logs" :
					$ArrayValues[]= $LANG["choice"][0];
					$ArrayValues[]= $LANG["choice"][1];
					$ArrayValues[]= $LANG["setup"][137];
					$out = $ArrayValues[$data["ITEM_$num"]];
					return "<center>".$out."</center>";
					break;
				
				// ** Display pic / link for exporting model
				case "glpi_plugin_tracker_agents.ID" :
					$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.agents.export.php' target='_blank'>
					<input type='hidden' name='agent' value='" . $data["ID"] . "' />
					<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
					</form></div>";
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($table.'.'.$field) {


				// ** Display entity name
				case "glpi_entities.name" :
					if ($data["ITEM_$num"] == '') {
						$out = getDropdownName("glpi_entities",$data["ITEM_$num"]);
						return "<center>".$out."</center>";
					}
					break;

			}
			break;

		// * Detail of ports history (plugins/tracker/report/plugin_tracker.switch_ports.history.php)
		case PLUGIN_TRACKER_SNMP_HISTORY :
			switch ($table.'.'.$field) {

				// ** Display switch and Port
				case "glpi_networking_ports.ID" :
					$Array_device = plugin_tracker_getUniqueObjectfieldsByportID($data["ITEM_$num"]);
					$CommonItem = new CommonItem;
					$CommonItem->getFromDB($Array_device["device_type"], $Array_device["on_device"]);
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
				case "glpi_plugin_tracker_snmp_history.Field" :
					if ($data["ITEM_$num"] == "0") {
						if (empty($data["ITEM_4"])) {
							return "<center><b>".$LANG['plugin_tracker']["history"][3]."</b></center>";
                  } else if (empty($data["ITEM_5"])) {
							return "<center><b>".$LANG['plugin_tracker']["history"][2]."</b></center>";
                  }
					}
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_tracker_snmp_history.old_value" :
					// TODO ADD LINK TO DEVICE
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_tracker_snmp_history.new_value" :
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
function plugin_tracker_getDropdown() {
	// Table => Name
	global $LANG;
	if (isset ($_SESSION["glpi_plugin_tracker_installed"]) && $_SESSION["glpi_plugin_tracker_installed"] == 1) {
		return array (
			"glpi_dropdown_plugin_tracker_snmp_version" => "SNMP version",
			"glpi_dropdown_plugin_tracker_mib_oid" => "OID MIB",
			"glpi_dropdown_plugin_tracker_mib_object" => "Objet MIB",
			"glpi_dropdown_plugin_tracker_mib_label" => "Label MIB"
		);
   } else {
		return array ();
   }
}

/* Cron */
function cron_plugin_tracker() {
   // TODO :Disable for the moment (may be check if functions is good or not
//	$ptud = new PluginTrackerUnknownDevice;
//   $ptud->CleanOrphelinsConnections();
//	$ptud->FusionUnknownKnownDevice();
//   #Clean server script processes history
//   $tracker_config_snmp_networking = new PluginTrackerConfigSNMPNetworking;
//   $tracker_config_snmp_networking->CleanHistory("history_process");
   return 1;
}



function plugin_tracker_install() {
	global $DB, $LANG, $CFG_GLPI;

	include_once (GLPI_ROOT."/inc/profile.class.php");
    /**
    *  List of all Trackers versions :
    *    1.0.0
    *    1.1.0 non exists glpi_plugin_tracker_agents (MySQL)
    *    2.0.0 non exists glpi_plugin_tracker_config_discovery (MySQL)
    *    2.0.1 Nothing
    *    2.0.2 config version field 2.0.2
    *    2.1.0 config version field 2.1.0
    *    2.1.1 config version field 2.1.1
    **/
   if (!TableExists("glpi_plugin_tracker_config")) {
      plugin_tracker_installing("2.2.0");
   } else {
      $config = new PluginTrackerConfig;
      if (!TableExists("glpi_plugin_tracker_agents")) {
         plugin_tracker_update("1.1.0");
      }
      if (!TableExists("glpi_plugin_tracker_config_discovery")) {
         plugin_tracker_update("2.0.0");
      }
      if (!FieldExists("glpi_plugin_tracker_config", "version")) {
         plugin_tracker_update("2.0.2");
      }
      if (FieldExists("glpi_plugin_tracker_config", "version")) {
         if ($config->getValue('version') == "2.0.2") {
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.0'
                        WHERE `ID`='1';");
         }
         if ($config->getValue('version') == "2.1.0") {
            plugin_tracker_update("2.1.1");
            $DB->query("UPDATE `glpi_plugin_tracker_config` SET version = '2.1.1' WHERE ID=1");
         }
         if ($config->getValue('version') == "2.1.1") {
            plugin_tracker_update("2.1.2");
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.2'
                        WHERE `ID`='1';");
         }
         if ($config->getValue('version') == "2.1.2") {
            plugin_tracker_update("2.1.2");
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.1.3'
                        WHERE `ID`='1';");
         }
         if ($config->getValue('version') == "2.1.3") {
            plugin_tracker_update("2.2.0");
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.2.0'
                        WHERE `ID`='1';");
         }
         if  ($config->getValue('version') == "0") {
            $DB->query("UPDATE `glpi_plugin_tracker_config`
                        SET `version` = '2.2.0'
                        WHERE `ID`='1';");
         }
      }
   }
   return true;
}



/**
* Check if Tracker need to be updated
*
* @param
*
* @return 0 (no need update) OR 1 (need update)
**/
function plugin_tracker_needUpdate() {

    /**
    *  List of all Trackers versions :
    *    1.0.0
    *    1.1.0 non exists glpi_plugin_tracker_agents (MySQL)
    *    2.0.0 non exists glpi_plugin_tracker_config_discovery (MySQL)
    *    2.0.1 Nothing
    *    2.0.2 config version field 2.0.2
    *    2.1.0 config version field 2.1.0
    *    2.1.1 config version field 2.1.1
    **/
	if (!TableExists("glpi_plugin_tracker_config")) {
		return 0; // Installation
   } else if (!TableExists("glpi_plugin_tracker_agents")) {
		return 1; //Update
   } else if (!TableExists("glpi_plugin_tracker_config_discovery")) {
//		return 1; // Update (Bug with new version SVN 2.1.4
   } else if (!FieldExists("glpi_plugin_tracker_config", "version")) {
      return 1; // Update
   } else if (FieldExists("glpi_plugin_tracker_config", "version")) {
      $config = new PluginTrackerConfig;
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
function plugin_get_headings_tracker($type,$ID,$withtemplate) {
	global $LANG;
	$configModules = new PluginTrackerConfigModules;

	switch ($type) {
		case COMPUTER_TYPE :
			if ($withtemplate) { //?
				return array();
			// Non template case
         } else {
//				if ((plugin_tracker_haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
					return array(
						1 => $LANG['plugin_tracker']["title"][5]
					);
//				}
			}
			break;

		case MONITOR_TYPE :
			if ($withtemplate) { //?
				return array();
			// Non template case
         } else {
//				if ((plugin_tracker_haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
					return array(
						1 => $LANG['plugin_tracker']["title"][5]
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
				if ((plugin_tracker_haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
					$array[1] = $LANG['plugin_tracker']["title"][0];
				}
            $array[2] = $LANG['plugin_tracker']["title"][5];
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
				if ((plugin_tracker_haveRight("snmp_printers", "r")) AND ($configModules->getValue("snmp") == "1")) {
					$array[1] = $LANG['plugin_tracker']["title"][0];
				}
            $array[2] = $LANG['plugin_tracker']["title"][5];
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
					1 => $LANG['plugin_tracker']["title"][1],
					);
         }
			break;
	}
	return false;	
}

// Define headings actions added by the plugin	 
function plugin_headings_actions_tracker($type) {

	$config = new PluginTrackerConfig;

	switch ($type) {
		case COMPUTER_TYPE :
			$array = array ();
//			if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
				$array = array (
					1 => "plugin_headings_tracker_trackerLocks"
				);
//			}
//			if (plugin_tracker_haveRight("printers_info", "r")) {
//				$array = array (
//					2 => "plugin_headings_tracker_computersInfo"
//				);
//			}
//			if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
//				$array = array (
//					1 => "plugin_headings_tracker_computerHistory"
//				);
//			}
			return $array;
			break;

		case MONITOR_TYPE :
			$array = array ();
//			if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
				$array = array (
					1 => "plugin_headings_tracker_trackerLocks"
				);
//			}
      case PRINTER_TYPE :
			$array = array ();
			if (plugin_tracker_haveRight("snmp_printers", "r")) {
				$array[1] = "plugin_headings_tracker_printerInfo";
			}
         $array[2] = "plugin_headings_tracker_trackerLocks";
			return $array;
			break;

		case NETWORKING_TYPE :
			if (plugin_tracker_haveRight("snmp_networking", "r")) {
				$array[1] = "plugin_headings_tracker_networkingInfo";
			}
         $array[2] = "plugin_headings_tracker_trackerLocks";
			return $array;
			break;

		case PROFILE_TYPE :
			return array(
				1 => "plugin_headings_tracker",
				);
			break;

	}
	return false;
}


function plugin_headings_tracker_computerHistory($type, $ID) {
	$computer_history = new PluginTrackerComputersHistory;
	$computer_history->showForm(COMPUTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerErrors($type, $ID) {
	$errors = new PluginTrackerErrors;
	$errors->showForm(COMPUTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerInfo($type, $ID) {
//	$plugin_tracker_printers = new PluginTrackerPrinters;
//	$plugin_tracker_printers->showFormPrinter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerInfo($type, $ID) {
	include_once(GLPI_ROOT."/inc/stat.function.php");
	$plugin_tracker_printers = new PluginTrackerPrinters;
	$plugin_tracker_printers->showFormPrinter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $ID);
	$plugin_tracker_printers->showFormPrinter_pagescounter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $ID);
}

function plugin_headings_tracker_printerHistory($type, $ID) {
	$print_history = new PluginTrackerPrintersHistory;
	$print_history->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerErrors($type, $ID) {
	$errors = new PluginTrackerErrors;
	$errors->showForm(PRINTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerCronConfig($type, $ID) {
	$print_config = new PluginTrackerPrintersHistoryConfig;
	$print_config->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_history_config.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_networkingInfo($type, $ID) {
	$snmp = new PluginTrackerNetworking;
	$snmp->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.switch_info.form.php', $ID);
}

function plugin_headings_tracker_networkingErrors($type, $ID) {
	$errors = new PluginTrackerErrors;
	$errors->showForm(NETWORKING_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_userHistory($type, $ID) {
	$computer_history = new PluginTrackerComputersHistory;
	$computer_history->showForm(USER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_trackerLocks($type, $ID) {
	$tracker_locks = new PluginTrackerLock();
	$tracker_locks->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.lock.form.php', $type, $ID);
}

function plugin_headings_tracker($type,$ID,$withtemplate=0) {
	global $CFG_GLPI;

	switch ($type) {
		case PROFILE_TYPE :
			$prof=new PluginTrackerProfile;
			if (!$prof->GetfromDB($ID)) {
				plugin_tracker_createaccess($ID);
         }
			$prof->showForm($CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.profile.php",$ID);		
		break;
	}
}


function plugin_tracker_MassiveActions($type) {
	global $LANG;
	switch ($type) {
		case NETWORKING_TYPE :
			return array (
            "plugin_tracker_get_model" => $LANG['plugin_tracker']["model_info"][14],
				"plugin_tracker_assign_model" => $LANG['plugin_tracker']["massiveaction"][1],
				"plugin_tracker_assign_auth" => $LANG['plugin_tracker']["massiveaction"][2]
			);
			break;

		case PRINTER_TYPE :
			return array (
            "plugin_tracker_get_model" => $LANG['plugin_tracker']["model_info"][14],
				"plugin_tracker_assign_model" => $LANG['plugin_tracker']["massiveaction"][1],
				"plugin_tracker_assign_auth" => $LANG['plugin_tracker']["massiveaction"][2]
			);
			break;

		case PLUGIN_TRACKER_MAC_UNKNOWN;
			return array (
				"plugin_tracker_discovery_import" => $LANG["buttons"][37]
			);
	}
	return array ();
}

function plugin_tracker_MassiveActionsDisplay($type, $action) {

	global $LANG, $CFG_GLPI, $DB;
	switch ($type) {
		case NETWORKING_TYPE :
			switch ($action) {

            case "plugin_tracker_get_model" :
               if(plugin_tracker_HaveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_tracker_assign_model" :
               if(plugin_tracker_HaveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_tracker_model_infos`
                                   WHERE `device_type`!='2'
                                         AND `device_type`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['ID'];
                  }
                  dropdownValue("glpi_plugin_tracker_model_infos", "snmp_model", "name",0,-1,'',$exclude_models);
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_tracker_assign_auth" :
               if(plugin_tracker_HaveRight("snmp_authentification","w")) {
                  plugin_tracker_snmp_auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

			}
			break;

		case PRINTER_TYPE :
			switch ($action) {

            case "plugin_tracker_get_model" :
               if(plugin_tracker_HaveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_tracker_assign_model" :
               if(plugin_tracker_HaveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_tracker_model_infos`
                                   WHERE `device_type`!='3'
                                         AND `device_type`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['ID'];
                  }
                  dropdownValue("glpi_plugin_tracker_model_infos", "snmp_model", "name",0,-1,'',$exclude_models);
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_tracker_assign_auth" :
               if(plugin_tracker_HaveRight("snmp_authentification","w")) {
                  plugin_tracker_snmp_auth_dropdown();
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;
			}
			break;

		case PLUGIN_TRACKER_MAC_UNKNOWN;
			switch ($action) {
				case "plugin_tracker_discovery_import" :
               if(plugin_tracker_HaveRight("snmp_discovery","w")) {
                  echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
					break;
			}
			break;
	}
	return "";
}

function plugin_tracker_MassiveActionsProcess($data) {
	global $LANG;
	switch ($data['action']) {

      case "plugin_tracker_get_model" :
         if ($data['device_type'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginTrackerModelInfos = new PluginTrackerModelInfos;
                  $PluginTrackerModelInfos->getrightmodel($key, NETWORKING_TYPE);
					}
				}
         } else if($data['device_type'] == PRINTER_TYPE) {
            foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
                  $PluginTrackerModelInfos = new PluginTrackerModelInfos;
                  $PluginTrackerModelInfos->getrightmodel($key, PRINTER_TYPE);
					}
				}
         }
         break;

		case "plugin_tracker_assign_model" :
			if ($data['device_type'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, NETWORKING_TYPE, "model", $data["snmp_model"]);
					}
				}
			} else if($data['device_type'] == PRINTER_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, PRINTER_TYPE, "model", $data["snmp_model"]);
					}
				}
			}
			break;
      
		case "plugin_tracker_assign_auth" :
			if ($data['device_type'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, NETWORKING_TYPE, "auth", $data["FK_snmp_connection"]);
					}
				}
			} else if($data['device_type'] == PRINTER_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, PRINTER_TYPE, "auth", $data["FK_snmp_connection"]);
					}
				}
			}
			break;
      
		case "plugin_tracker_discovery_import" :
         if(plugin_tracker_HaveRight("snmp_discovery","w")) {
            $Import = 0;
            $NoImport = 0;
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  list($Import, $NoImport) = plugin_tracker_discovery_import($key,$Import,$NoImport);
               }
            }
            addMessageAfterRedirect($LANG['plugin_tracker']["discovery"][5]." : ".$Import);
            addMessageAfterRedirect($LANG['plugin_tracker']["discovery"][9]." : ".$NoImport);
         }
			break;
	}
}

// How to display specific update fields ?
// Massive Action functions
function plugin_tracker_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield) {
	global $LINK_ID_TABLE,$LANG;
	// Table fields
	//echo $table.".".$field."<br/>";
	switch ($table.".".$field) {

		case 'glpi_plugin_tracker_snmp_connection.name':
			dropdownValue("glpi_plugin_tracker_snmp_connection",$linkfield);
			return true;
			break;

		case 'glpi_plugin_tracker_model_infos.name':
			dropdownValue("glpi_plugin_tracker_model_infos",$linkfield,'',0);
			return true;
			break;

		case 'glpi_plugin_tracker_unknown_device.type' :
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			dropdownDeviceTypes('type',$linkfield,$type_list);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.ID' :
			dropdownValue("glpi_plugin_tracker_agents",$linkfield,'',0);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.nb_process_query' :
			dropdownInteger("nb_process_query", $linkfield,1,200);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.nb_process_discovery' :
			dropdownInteger("nb_process_discovery", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.logs' :
			$ArrayValues[]= $LANG["choice"][0];
			$ArrayValues[]= $LANG["choice"][1];
			$ArrayValues[]= $LANG["setup"][137];
			dropdownArrayValues('logs',$ArrayValues,$linkfield);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.core_discovery' :
			dropdownInteger("core_discovery", $linkfield,1,32);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.core_query' :
			dropdownInteger("core_query", $linkfield,1,32);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.threads_discovery' :
			dropdownInteger("threads_discovery", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_tracker_agents.threads_query' :
			dropdownInteger("threads_query", $linkfield,1,400);
			return true;
			break;

		case 'glpi_plugin_tracker_discovery.FK_snmp_connection' :
			$plugin_tracker_snmp = new PluginTrackerSNMPAuth;
			echo $plugin_tracker_snmp->selectbox();
			return true;
			break;

		case 'glpi_plugin_tracker_model_infos.device_type' :
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			dropdownDeviceTypes('type',$linkfield,$type_list);
			return true;
			break;
	}
	return false;
}



function plugin_tracker_addSelect($type,$ID,$num) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

			// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					return "GROUP_CONCAT( DISTINCT TRACKER_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
               return "GROUP_CONCAT( DISTINCT TRACKER_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;
			}
			break;
		// * PRINTER List (front/printer.php)
      case PRINTER_TYPE :
         switch ($table.".".$field) {

         // ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               return "GROUP_CONCAT( DISTINCT TRACKER_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

				// ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               return "GROUP_CONCAT( DISTINCT TRACKER_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
         break;

		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				case "glpi_networking.device" :
					return "GROUP_CONCAT( DISTINCT TRACKER_12.on_device SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				case "glpi_networking_ports.netport" :
					return "GROUP_CONCAT( DISTINCT TRACKER_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

			}
			break;
	}
	return "";
}


function plugin_tracker_forceGroupBy($type) {
    switch ($type) {

      case COMPUTER_TYPE :
         // ** Tracker - switch
         return "GROUP BY glpi_computers.id";
         break;

        case PRINTER_TYPE :
            // ** Tracker - switch
            return "GROUP BY glpi_printers.id";
            break;

    }
    return false;
}


// Search modification for plugin Tracker

function plugin_tracker_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {


//	echo "Left Join : ".$new_table.".".$linkfield."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($new_table.".".$linkfield) {
				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON glpi_networking_ports.ID = TRACKER_11.end1 OR glpi_networking_ports.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = glpi_networking_ports.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_10 ON (TRACKER_10.on_device = glpi_computers.ID AND TRACKER_10.device_type='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON TRACKER_10.ID = TRACKER_11.end1 OR TRACKER_10.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = TRACKER_10.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";
               }
               break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
               $table_networking_ports = 0;
               $table_tracker_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_tracker_networking.ID") {
                     $table_tracker_networking = 1;
                  }
               }
               if ($table_tracker_networking == "1") {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID=TRACKER_12.ID ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON glpi_networking_ports.ID = TRACKER_21.end1 OR glpi_networking_ports.ID = TRACKER_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = glpi_networking_ports.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_20 ON (TRACKER_20.on_device = glpi_computers.ID AND TRACKER_20.device_type='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON TRACKER_20.ID = TRACKER_21.end1 OR TRACKER_20.ID = TRACKER_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = TRACKER_20.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";

               }
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_networking.ID" :
					return " LEFT JOIN glpi_plugin_tracker_networking ON (glpi_networking.ID = glpi_plugin_tracker_networking.FK_networking) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					return " LEFT JOIN glpi_plugin_tracker_networking AS gptn_model ON (glpi_networking.ID = gptn_model.FK_networking) ".
						" LEFT JOIN glpi_plugin_tracker_model_infos ON (gptn_model.FK_model_infos = glpi_plugin_tracker_model_infos.ID) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					return " LEFT JOIN glpi_plugin_tracker_networking AS gptn_auth ON glpi_networking.ID = gptn_auth.FK_networking ".
						" LEFT JOIN glpi_plugin_tracker_snmp_connection ON gptn_auth.FK_snmp_connection = glpi_plugin_tracker_snmp_connection.ID ";
					break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_printers.ID" :
					return " LEFT JOIN glpi_plugin_tracker_printers ON (glpi_printers.ID = glpi_plugin_tracker_printers.FK_printers) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					return " LEFT JOIN glpi_plugin_tracker_printers AS gptp_model ON (glpi_printers.ID = gptp_model.FK_printers) ".
						" LEFT JOIN glpi_plugin_tracker_model_infos ON (gptp_model.FK_model_infos = glpi_plugin_tracker_model_infos.ID) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					return " LEFT JOIN glpi_plugin_tracker_printers AS gptp_auth ON glpi_printers.ID = gptp_auth.FK_printers ".
						" LEFT JOIN glpi_plugin_tracker_snmp_connection ON gptp_auth.FK_snmp_connection = glpi_plugin_tracker_snmp_connection.ID ";
					break;

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON glpi_networking_ports.ID = TRACKER_11.end1 OR glpi_networking_ports.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = glpi_networking_ports.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_10 ON (glpi_printers.ID = TRACKER_10.on_device AND TRACKER_10.device_type='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON TRACKER_10.ID = TRACKER_11.end1 OR TRACKER_10.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = TRACKER_10.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";
               }
               break;

               // ** Tracker - switch port
               case "glpi_plugin_tracker_networking_ports.ID" :
                  $table_networking_ports = 0;
                  $table_tracker_networking = 0;
                  foreach ($already_link_tables AS $num=>$tmp_table) {
                     if ($tmp_table == "glpi_networking_ports.") {
                        $table_networking_ports = 1;
                     }
                     if ($tmp_table == "glpi_plugin_tracker_networking.ID") {
                        $table_tracker_networking = 1;
                     }
                  }
                  if ($table_tracker_networking == "1") {
                     return " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID=TRACKER_12.ID ";
                  } else if ($table_networking_ports == "1") {
                     return " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON glpi_networking_ports.ID = TRACKER_21.end1 OR glpi_networking_ports.ID = TRACKER_21.end2 ".
                        " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = glpi_networking_ports.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
                  } else {
                     return " LEFT JOIN glpi_networking_ports AS TRACKER_20 ON (TRACKER_20.on_device = glpi_computers.ID AND TRACKER_20.device_type='".PRINTER_TYPE."') ".
                      " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON TRACKER_20.ID = TRACKER_21.end1 OR TRACKER_20.ID = TRACKER_21.end2 ".
                        " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = TRACKER_20.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
                  }
                  break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($new_table.".".$linkfield) {

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON glpi_networking_ports.ID = TRACKER_11.end1 OR glpi_networking_ports.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = glpi_networking_ports.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";

               } else {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_10 ON (glpi_printers.ID = TRACKER_10.on_device AND TRACKER_10.device_type='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networking_wire AS TRACKER_11 ON TRACKER_10.ID = TRACKER_11.end1 OR TRACKER_10.ID = TRACKER_11.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = TRACKER_10.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END
                     LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device=TRACKER_13.ID";
               }
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               $table_networking_ports = 0;
               $table_tracker_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networking_ports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_tracker_networking.ID") {
                     $table_tracker_networking = 1;
                  }
               }
               if ($table_tracker_networking == "1") {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID=TRACKER_12.ID ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON glpi_networking_ports.ID = TRACKER_21.end1 OR glpi_networking_ports.ID = TRACKER_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = glpi_networking_ports.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networking_ports AS TRACKER_20 ON (TRACKER_20.on_device = glpi_computers.ID AND TRACKER_20.device_type='".PRINTER_TYPE."') ".
                   " LEFT JOIN glpi_networking_wire AS TRACKER_21 ON TRACKER_20.ID = TRACKER_21.end1 OR TRACKER_20.ID = TRACKER_21.end2 ".
                     " LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = TRACKER_20.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
               }
               break;

			}
			break;


		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($new_table.".".$linkfield) {

				// ** Location of switch
				case "glpi_dropdown_locations.FK_networking_ports" :
					return " LEFT JOIN glpi_networking_ports ON (glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.ID) ".
						" LEFT JOIN glpi_networking ON glpi_networking_ports.on_device = glpi_networking.ID".
						" LEFT JOIN glpi_dropdown_locations ON glpi_dropdown_locations.ID = glpi_networking.location";
					break;

			}
			break;

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($new_table.".".$linkfield) {

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_tracker_agents.FK_tracker_agents" :
					return " LEFT JOIN glpi_plugin_tracker_agents ON (glpi_plugin_tracker_agents.ID = glpi_plugin_tracker_rangeip.FK_tracker_agents) ";
					break;

			}
			break;
	}
	return "";
}



function plugin_tracker_addOrderBy($type,$ID,$order,$key=0) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "ORDER BY : ".$table.".".$field;

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					return " ORDER BY TRACKER_12.on_device $order ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					return " ORDER BY TRACKER_22.".$field." $order ";
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.".".$field) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_networking.FK_networking" :
					return " ORDER BY glpi_plugin_tracker_networking.last_tracker_update $order ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					return " ORDER BY glpi_plugin_tracker_model_infos.name $order ";
					break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.".".$field) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_printers.FK_printers" :
					return " ORDER BY glpi_plugin_tracker_printers.last_tracker_update $order ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					return " ORDER BY glpi_plugin_tracker_model_infos.name $order ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					return " ORDER BY glpi_plugin_tracker_snmp_connection.name $order ";
					break;

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               return " ORDER BY TRACKER_12.on_device $order ";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               return " ORDER BY TRACKER_22.".$field." $order ";
               break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               return " ORDER BY TRACKER_12.on_device $order ";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               return " ORDER BY TRACKER_22.".$field." $order ";
               break;

			}
			break;

		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($table.".".$field) {

				// ** Location of switch
				case "glpi_dropdown_locations.ID" :
					return " ORDER BY glpi_dropdown_locations.name $order ";
					break;

			}
			break;

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($table.".".$field) {
			
				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_tracker_agents.ID" :
					return " ORDER BY glpi_plugin_tracker_agents.name $order ";
					break;

			}
			break;

		// * Detail of ports history (plugins/tracker/report/plugin_tracker.switch_ports.history.php)
		case PLUGIN_TRACKER_SNMP_HISTORY :
			switch ($table.".".$field) {

				// ** Display switch and Port
				case "glpi_plugin_tracker_snmp_history.ID" :
					return " ORDER BY glpi_plugin_tracker_snmp_history.ID $order ";
					break;
				case "glpi_networking_ports.ID" :
					return " ORDER BY glpi_networking.name,glpi_networking_ports.name $order ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_tracker_snmp_history.Field" :
					return " ORDER BY glpi_plugin_tracker_snmp_history.Field $order ";
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_tracker_snmp_history.old_value" :
					return " ORDER BY glpi_plugin_tracker_snmp_history.old_value $order ";
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_tracker_snmp_history.new_value" :
					return " ORDER BY glpi_plugin_tracker_snmp_history.new_value $order ";
					break;

				case "glpi_plugin_tracker_snmp_history.date_mod" :
				return " ORDER BY glpi_plugin_tracker_snmp_history.date_mod $order ";
						break;

			}
	}
	return "";
}



function plugin_tracker_addWhere($link,$nott,$type,$ID,$val) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

//	echo "add where : ".$table.".".$field."<br/>";
	$SEARCH=makeTextSearch($val,$nott);

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR TRACKER_12.on_device IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR TRACKER_12.on_device IS NOT NULL";
					}
					return $link." (TRACKER_13.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR TRACKER_22.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR TRACKER_22.name IS NOT NULL";
					}
					return $link." (TRACKER_22.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.".".$field) {

         // ** Tracker - last inventory
				case "glpi_plugin_tracker_networking.FK_networking" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NOT NULL";
					}
					return $link." ($table.last_tracker_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_networking.FK_snmp_connection" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_tracker_snmp_connection.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_tracker_snmp_connection.name IS NOT NULL";
					}
					return $link." (glpi_plugin_tracker_snmp_connection.name  LIKE '%".$val."%' $ADD ) ";
					break;

            // ** Tracker - CPU
            case "glpi_plugin_tracker_networking.cpu":

               break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.".".$field) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_printers.FK_printers" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NOT NULL";
					}
					return $link." ($table.last_tracker_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR TRACKER_12.on_device IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR TRACKER_12.on_device IS NOT NULL";
               }
               return $link." (TRACKER_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR TRACKER_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR TRACKER_22.name IS NOT NULL";
               }
               return $link." (TRACKER_22.name  LIKE '%".$val."%' $ADD ) ";
               break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** Tracker - switch
            case "glpi_plugin_tracker_networking.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR TRACKER_12.on_device IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR TRACKER_12.on_device IS NOT NULL";
               }
               return $link." (TRACKER_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** Tracker - switch port
            case "glpi_plugin_tracker_networking_ports.ID" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR TRACKER_22.name IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR TRACKER_22.name IS NOT NULL";
               }
               return $link." (TRACKER_22.name  LIKE '%".$val."%' $ADD ) ";
               break;
			}
			break;

		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($table.".".$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_tracker_networking_ports.ID" :
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_tracker_networking_ports.FK_networking_ports" :
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

				case "glpi_plugin_tracker_networking_ports.lastup" :
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

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($table.".".$field) {

				// ** Name of range IP and link to form
				case "glpi_plugin_tracker_rangeip.name" :
					break;

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_tracker_agents.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}
			break;

		// * Detail of ports history (plugins/tracker/report/plugin_tracker.switch_ports.history.php)
		case PLUGIN_TRACKER_SNMP_HISTORY :
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
				case "glpi_plugin_tracker_snmp_history.Field" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NULL ";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NOT NULL ";
					}
					if (!empty($val)) {
                  include (GLPI_ROOT . "/plugins/tracker/inc_constants/plugin_tracker.snmp.mapping.constant.php");
						$val = $TRACKER_MAPPING[NETWORKING_TYPE][$val]['name'];
               }
					return $link." ($table.$field = '".addslashes($val)."' $ADD ) ";
					break;

			}
	}
	return "";
}

function plugin_pre_item_purge_tracker($parm) {
	global $DB;

	if (isset($parm["_item_type_"])) {
		switch ($parm["_item_type_"]) {
			case NETWORKING_TYPE :
				// Delete all ports
				$query_delete = "DELETE FROM `glpi_plugin_tracker_networking`
                             WHERE `FK_networking`='".$parm["ID"]."';";
				$DB->query($query_delete);

				$query_select = "SELECT `glpi_plugin_tracker_networking_ports`.`ID`
                             FROM `glpi_plugin_tracker_networking_ports`
                                  LEFT JOIN `glpi_networking_ports`
                                            ON `glpi_networking_ports`.`ID` = `FK_networking_ports`
                             WHERE `on_device`='".$parm["ID"]."'
                                   AND `device_type`='".NETWORKING_TYPE."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_tracker_networking_ports`
                                WHERE `ID`='".$data["ID"]."';";
					$DB->query($query_delete);
				}

				$query_select = "SELECT `glpi_plugin_tracker_networking_ifaddr`.`ID`
                             FROM `glpi_plugin_tracker_networking_ifaddr`
                                  LEFT JOIN `glpi_networking`
                                            ON `glpi_networking`.`ID` = `FK_networking`
                             WHERE `FK_networking`='".$parm["ID"]."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_tracker_networking_ifaddr`
                                WHERE `ID`='".$data["ID"]."';";
					$DB->query($query_delete);
				}
            break;

         case PLUGIN_TRACKER_MAC_UNKNOWN :
            // Delete ports and connections if exists
            $np=new Netport;
            $query = "SELECT `ID`
                      FROM `glpi_networking_ports`
                      WHERE `on_device` = '".$parm["ID"]."'
                            AND `device_type` = '".PLUGIN_TRACKER_MAC_UNKNOWN."';";
            $result = $DB->query($query);
            while ($data = $DB->fetch_array($result)) {
               removeConnector($data["ID"]);
               $np->delete(array("ID"=>$data["ID"]));
            }
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
function plugin_item_update_tracker($parm) {
   if (isset($_SESSION["glpiID"]) AND $_SESSION["glpiID"]!='') { // manual task
      // lock fields which have been updated
      $type=$parm['type'];
      $ID=$parm['ID'];
      $fieldsToLock=$parm['updates'];
      $lockables = plugin_tracker_lockable_getLockableFields('', $type);
      $fieldsToLock = array_intersect($fieldsToLock, $lockables); // do not lock unlockable fields
      plugin_tracker_lock_addLocks($type, $ID, $fieldsToLock);
   }
}

?>