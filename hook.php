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

function plugin_tracker_getSearchOption()
{
	global $LANG,$LANGTRACKER;
	$sopt = array ();

	$config = new plugin_tracker_config;

	// Part header
	$sopt[PLUGIN_TRACKER_ERROR_TYPE]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['field'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['linkfield'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][1]['name'] = $LANGTRACKER["errors"][1];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][2]['name'] = $LANG["common"][2];

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
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][6]['name'] = $LANGTRACKER["errors"][2];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['field'] = 'first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['linkfield'] = 'first_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][7]['name'] = $LANGTRACKER["errors"][3];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['table'] = 'glpi_plugin_tracker_errors';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['field'] = 'last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['linkfield'] = 'last_pb_date';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][8]['name'] = $LANGTRACKER["errors"][4];

	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['field'] = 'completename';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_ERROR_TYPE][80]['name'] = $LANG["entity"][0];

	$sopt[PLUGIN_TRACKER_MODEL]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_MODEL][1]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MODEL][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_MODEL][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_MODEL][2]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MODEL][2]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MODEL][2]['name'] = $LANG["common"][2];
	
	$sopt[PLUGIN_TRACKER_MODEL][3]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][3]['field'] = 'device_type';
	$sopt[PLUGIN_TRACKER_MODEL][3]['linkfield'] = 'device_type';
	$sopt[PLUGIN_TRACKER_MODEL][3]['name'] = $LANG["common"][17];

	$sopt[PLUGIN_TRACKER_MODEL][5]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][5]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MODEL][5]['linkfield'] = 'EXPORT';
	$sopt[PLUGIN_TRACKER_MODEL][5]['name'] = $LANG["buttons"][31];

	$sopt[PLUGIN_TRACKER_MODEL][6]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][6]['field'] = 'activation';
	$sopt[PLUGIN_TRACKER_MODEL][6]['linkfield'] = 'activation';
	$sopt[PLUGIN_TRACKER_MODEL][6]['name'] = $LANGTRACKER["model_info"][11];
	
	$sopt[PLUGIN_TRACKER_MODEL][7]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_MODEL][7]['field'] = 'discovery_key';
	$sopt[PLUGIN_TRACKER_MODEL][7]['linkfield'] = 'discovery_key';
	$sopt[PLUGIN_TRACKER_MODEL][7]['name'] = $LANGTRACKER["model_info"][12];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][2]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['table'] = 'glpi_dropdown_plugin_tracker_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['linkfield'] = 'FK_snmp_version';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][3]['name'] = $LANGTRACKER["model_info"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['field'] = 'community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['linkfield'] = 'community';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][4]['name'] = $LANGTRACKER["snmpauth"][1];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['field'] = 'sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['linkfield'] = 'sec_name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][5]['name'] = $LANGTRACKER["snmpauth"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['table'] = 'glpi_dropdown_plugin_tracker_snmp_auth_sec_level';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['linkfield'] = 'sec_level';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][6]['name'] = $LANGTRACKER["snmpauth"][3];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['table'] = 'glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['linkfield'] = 'auth_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][7]['name'] = $LANGTRACKER["snmpauth"][4];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['field'] = 'auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['linkfield'] = 'auth_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][8]['name'] = $LANGTRACKER["snmpauth"][5];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['table'] = 'glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['linkfield'] = 'priv_protocol';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][9]['name'] = $LANGTRACKER["snmpauth"][6];

	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['field'] = 'priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['linkfield'] = 'priv_passphrase';
	$sopt[PLUGIN_TRACKER_SNMP_AUTH][10]['name'] = $LANGTRACKER["snmpauth"][7];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['table'] = 'glpi_plugin_tracker_unknown_device';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['field'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['linkfield'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][2]['name'] = $LANG["common"][26];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['field'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][3]['name'] = $LANG["networking"][14];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['field'] = 'ifmac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][4]['name'] = $LANG["networking"][15];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['table'] = 'glpi_networking';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][5]['name'] = $LANGTRACKER["title"][0]." - ".$LANG["reports"][52];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['table'] = 'glpi_networking_ports';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOWN][6]['name'] = $LANGTRACKER["title"][0]." - ".$LANG["reports"][46];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][1]['name'] = $LANG["common"][16];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][2]['name'] = $LANGTRACKER["snmp"][42];	

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][3]['name'] = $LANGTRACKER["snmp"][43];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][4]['name'] = $LANGTRACKER["snmp"][44];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][5]['name'] = $LANGTRACKER["snmp"][45];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][6]['name'] = $LANGTRACKER["snmp"][46];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][7]['name'] = $LANGTRACKER["snmp"][47];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][8]['name'] = $LANGTRACKER["snmp"][48];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][9]['name'] = $LANGTRACKER["snmp"][49];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][10]['name'] = $LANGTRACKER["snmp"][51];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][11]['name'] = $LANGTRACKER["mapping"][115];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][12]['name'] = $LANG["networking"][17];
	
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][13]['name'] = $LANGTRACKER["snmp"][50];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS][14]['name'] = $LANG["networking"][56];


	$sopt[PLUGIN_TRACKER_SNMP_AGENTS]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][1]['name'] = $LANG["common"][16];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][2]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][2]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][2]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['field'] = 'core_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['linkfield'] = 'core_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['name'] = $LANGTRACKER["agents"][11];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['field'] = 'threads_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['linkfield'] = 'threads_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['name'] = $LANGTRACKER["agents"][3];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['field'] = 'core_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['linkfield'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['name'] = $LANGTRACKER["agents"][10];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['field'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['linkfield'] = 'threads_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['name'] = $LANGTRACKER["agents"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['field'] = 'fragment';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['linkfield'] = 'fragment';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['name'] = $LANGTRACKER["agents"][8];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['field'] = 'last_agent_update';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['linkfield'] = 'last_agent_update';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['name'] = $LANGTRACKER["agents"][4];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['field'] = 'tracker_agent_version';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['linkfield'] = 'tracker_agent_version';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['name'] = $LANGTRACKER["agents"][5];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['field'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['linkfield'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][10]['name'] = $LANGTRACKER["agents"][6];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['field'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['linkfield'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][11]['name'] = $LANG["Menu"][30];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['linkfield'] = 'EXPORT';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][12]['name'] = $LANGTRACKER["agents"][7];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][1]['name'] = $LANG["common"][16];
	
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['field'] = 'ifaddr_start';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['linkfield'] = 'ifaddr_start';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][2]['name'] = $LANGTRACKER["rangeip"][0];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['field'] = 'ifaddr_end';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['linkfield'] = 'ifaddr_end';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][3]['name'] = $LANGTRACKER["rangeip"][1];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][4]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][4]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][4]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][4]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['linkfield'] = 'FK_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['name'] = $LANG["ocsng"][49];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['field'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['linkfield'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['name'] = $LANGTRACKER["discovery"][3];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['field'] = 'query';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['linkfield'] = 'query';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['name'] = $LANGTRACKER["rangeip"][3];
	
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][8]['name'] = $LANG["entity"][0];


	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][1]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][1]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][1]['linkfield'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][1]['name'] = $LANG["common"][16];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][2]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][2]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][2]['name'] = $LANG["common"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][3]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][3]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][3]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][3]['name'] = $LANG["entity"][0];
	
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][4]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][4]['field'] = 'date';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][4]['linkfield'] = 'date';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][4]['name'] = $LANG["common"][27];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][5]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][5]['field'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][5]['linkfield'] = 'ifaddr';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][5]['name'] = $LANG["networking"][14];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][6]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][6]['field'] = 'descr';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][6]['linkfield'] = 'descr';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][6]['name'] = $LANG["joblist"][6];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][7]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][7]['field'] = 'serialnumber';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][7]['linkfield'] = 'serialnumber';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][7]['name'] = $LANG["common"][19];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][8]['table'] = 'glpi_plugin_tracker_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][8]['field'] = 'type';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][8]['linkfield'] = 'type';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][8]['name'] = $LANG["common"][17];

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][9]['table'] = 'glpi_plugin_tracker_model_infos';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][9]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][9]['linkfield'] = 'FK_model_infos';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][9]['name'] = $LANGTRACKER["model_info"][4];

	if ($config->getValue("authsnmp") == "file")
	{
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['table'] = 'glpi_plugin_tracker_discovery';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['field'] = 'FK_snmp_connection';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['linkfield'] = 'FK_snmp_connection';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['name'] = $LANGTRACKER["model_info"][3];
	}
	else
	{
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['table'] = 'glpi_plugin_tracker_snmp_connection';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['field'] = 'name';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['linkfield'] = 'FK_snmp_connection';
		$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['name'] = $LANGTRACKER["model_info"][3];
	}

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['field'] = 'process_number';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['linkfield'] = 'process_number';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['name'] = $LANGTRACKER["processes"][1];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['linkfield'] = 'FK_agent';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['name'] = $LANG["ocsng"][49];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][3]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][3]['field'] = 'status';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][3]['linkfield'] = 'status';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][3]['name'] = $LANG["joblist"][0];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][4]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][4]['field'] = 'start_time';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][4]['linkfield'] = 'start_time';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][4]['name'] = $LANGTRACKER["processes"][4];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][5]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][5]['field'] = 'end_time';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][5]['linkfield'] = 'end_time';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][5]['name'] = $LANGTRACKER["processes"][5];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][6]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][6]['field'] = 'discovery_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][6]['linkfield'] = 'discovery_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][6]['name'] = $LANGTRACKER["discovery"][3];
	
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][7]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][7]['field'] = 'networking_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][7]['linkfield'] = 'networking_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][7]['name'] = $LANGTRACKER["processes"][21];	
/*
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['field'] = 'networking_ports_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['linkfield'] = 'networking_ports_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['name'] = $LANGTRACKER["processes"][8];	
*/
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][9]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][9]['field'] = 'errors';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][9]['linkfield'] = 'errors';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][9]['name'] = $LANGTRACKER["processes"][22];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][10]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][10]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][10]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][10]['name'] = $LANGTRACKER["processes"][10];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][11]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][11]['field'] = 'start_time_discovery';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][11]['linkfield'] = 'start_time_discovery';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][11]['name'] = $LANGTRACKER["processes"][23];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][12]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][12]['field'] = 'start_time_query';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][12]['linkfield'] = 'start_time_query';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][12]['name'] = $LANGTRACKER["processes"][24];	
	

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][1]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][1]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][1]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][1]['name'] = "ID";

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
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][4]['name'] = $LANGTRACKER["history"][0];
	
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['field'] = 'new_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['linkfield'] = 'new_value';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][5]['name'] = $LANGTRACKER["history"][1];
	
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['table'] = 'glpi_plugin_tracker_snmp_history';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['field'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['linkfield'] = 'date_mod';
	$sopt[PLUGIN_TRACKER_SNMP_HISTORY][6]['name'] = $LANG["common"][27];


	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][1]['name'] = $LANG["reports"][52];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['field'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['linkfield'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][2]['name'] = $LANG["setup"][175];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['table'] = 'glpi_dropdown_locations';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['linkfield'] = 'FK_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][3]['name'] = $LANG["common"][15];

	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][4]['table'] = 'glpi_plugin_tracker_networking_ports';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][4]['field'] = 'lastup';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][4]['linkfield'] = 'lastup';
	$sopt[PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2][4]['name'] = $LANG["login"][0];


	$sopt[NETWORKING_TYPE][5190]['table']='glpi_plugin_tracker_model_infos';
	$sopt[NETWORKING_TYPE][5190]['field']='ID';
	$sopt[NETWORKING_TYPE][5190]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5190]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][19];

	if ($config->getValue("authsnmp") == "file")
	{
		$sopt[NETWORKING_TYPE][5191]['table'] = 'glpi_plugin_tracker_networking';
		$sopt[NETWORKING_TYPE][5191]['field'] = 'FK_snmp_connection';
		$sopt[NETWORKING_TYPE][5191]['linkfield'] = 'ID';
		$sopt[NETWORKING_TYPE][5191]['name'] = $LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][20];
	}
	else
	{
		$sopt[NETWORKING_TYPE][5191]['table']='glpi_plugin_tracker_snmp_connection';
		$sopt[NETWORKING_TYPE][5191]['field']='name';
		$sopt[NETWORKING_TYPE][5191]['linkfield']='ID';
		$sopt[NETWORKING_TYPE][5191]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][20];
	}

	$sopt[NETWORKING_TYPE][5194]['table']='glpi_plugin_tracker_networking';
	$sopt[NETWORKING_TYPE][5194]['field']='FK_networking';
	$sopt[NETWORKING_TYPE][5194]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5194]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["snmp"][53];

	$sopt[NETWORKING_TYPE][5195]['table']='glpi_plugin_tracker_networking';
	$sopt[NETWORKING_TYPE][5195]['field']='cpu';
	$sopt[NETWORKING_TYPE][5195]['linkfield']='ID';
	$sopt[NETWORKING_TYPE][5195]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["snmp"][13];


	$sopt[PRINTER_TYPE][5190]['table']='glpi_plugin_tracker_model_infos';
	$sopt[PRINTER_TYPE][5190]['field']='ID';
	$sopt[PRINTER_TYPE][5190]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5190]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][19];

	if ($config->getValue("authsnmp") == "file")
	{
		$sopt[PRINTER_TYPE][5191]['table'] = 'glpi_plugin_tracker_printers';
		$sopt[PRINTER_TYPE][5191]['field'] = 'FK_snmp_connection';
		$sopt[PRINTER_TYPE][5191]['linkfield'] = 'ID';
		$sopt[PRINTER_TYPE][5191]['name'] = $LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][20];
	}
	else
	{
		$sopt[PRINTER_TYPE][5191]['table']='glpi_plugin_tracker_snmp_connection';
		$sopt[PRINTER_TYPE][5191]['field']='ID';
		$sopt[PRINTER_TYPE][5191]['linkfield']='ID';
		$sopt[PRINTER_TYPE][5191]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["profile"][20];
	}

	$sopt[PRINTER_TYPE][5194]['table']='glpi_plugin_tracker_printers';
	$sopt[PRINTER_TYPE][5194]['field']='FK_printers';
	$sopt[PRINTER_TYPE][5194]['linkfield']='ID';
	$sopt[PRINTER_TYPE][5194]['name']=$LANGTRACKER["title"][0]." - ".$LANGTRACKER["snmp"][53];

	$sopt[COMPUTER_TYPE][5192]['table']='glpi_plugin_tracker_networking';
	$sopt[COMPUTER_TYPE][5192]['field']='ID';
	$sopt[COMPUTER_TYPE][5192]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5192]['name']=$LANGTRACKER["title"][0]." - ".$LANG["reports"][52];	
	
	$sopt[COMPUTER_TYPE][5193]['table']='glpi_plugin_tracker_networking_ports';
	$sopt[COMPUTER_TYPE][5193]['field']='ID';
	$sopt[COMPUTER_TYPE][5193]['linkfield']='ID';
	$sopt[COMPUTER_TYPE][5193]['name']=$LANGTRACKER["title"][0]." - ".$LANG["reports"][46];	
	
	
	return $sopt;
}


function plugin_tracker_giveItem($type, $field, $data, $num, $linkfield = "")
{
	global $CFG_GLPI, $LANG, $INFOFORM_PAGES, $LANGTRACKER, $DB;

//	echo "GiveItem : ".$field."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($field) {
				
				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");

					$netport = new Netport;
					
					$netport->getDeviceData($data["ITEM_$num"],NETWORKING_TYPE);

					$out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$data["ITEM_$num"]."\">";
					$out .=  $netport->device_name;
					if ($CFG_GLPI["view_ID"]) $out .= " (".$data["ITEM_$num"].")";
					$out .=  "</a><br/>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					if (!empty($data["ITEM_$num"]))
					{
						$np = new Netport;
						$np->getFromDB($data["ITEM_$num"]);
						$out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$np->fields["name"]."</a><br/>";
					}
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($field) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_networking.FK_networking" :
					$query = "SELECT * FROM glpi_plugin_tracker_networking
					WHERE FK_networking = '".$data["ID"]."' ";
					if ($result = $DB->query($query))
						$data2=$DB->fetch_array($result);

					$last_date = "";
					if (isset($data2["last_tracker_update"]))
						$last_date = $data2["last_tracker_update"];
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$plugin_tracker_snmp = new plugin_tracker_snmp;
					$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_model_infos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.name" :
					$plugin_tracker_snmp = new plugin_tracker_snmp_auth;
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
			switch ($field) {

				// ** Tracker - last inventory
				case "glpi_plugin_tracker_printers.FK_printers" :
					$query = "SELECT * FROM glpi_plugin_tracker_printers
					WHERE FK_printers = '".$data["ID"]."' ";
					if ($result = $DB->query($query))
						$data2=$DB->fetch_array($result);

					$last_date = "";
					if (isset($data2["last_tracker_update"]))
						$last_date = $data2["last_tracker_update"];
					$out = "<div align='center'>" .convDateTime($last_date) . "</div>";
					return $out;
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$plugin_tracker_snmp = new plugin_tracker_snmp;
					$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($data["ID"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/tracker/front/plugin_tracker.models.form.php?ID=" . $FK_model_DB . "\">";
					$out .= getDropdownName("glpi_plugin_tracker_model_infos", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					$plugin_tracker_snmp = new plugin_tracker_snmp_auth;
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
			switch ($field) {

				// ** Name of model and link to form
				case "glpi_plugin_tracker_model_infos.name" :
					if (empty ($data["ITEM_$num"]))
						$out = "";
					else
					{
						$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/" . $INFOFORM_PAGES[$type] . "?ID=" . $data['ID'] . "\">";
						$out .= $data["ITEM_$num"];
						if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
							$out .= " (" . $data["ID"] . ")";
						$out .= "</a>";
					}
					return "<center>".$out."</center>";
					break;

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_tracker_model_infos.device_type" :
					$out = '<center> ';
					switch ($data["ITEM_$num"])
					{
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

				// ** Display yes/no activation of model
				case "glpi_plugin_tracker_model_infos.activation" :
					$out = getYesNo($data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

			}
			break;


		// * Authentification List (plugins/tracker/front/plugin_tracker.snmp_auth.php)
		case PLUGIN_TRACKER_SNMP_AUTH :
			switch ($field) {

				// ** Name of authentification and link to form
				case "glpi_plugin_tracker_snmp_connection.name" :
					if (empty ($data["ITEM_$num"]))
						$out = "";
					else
					{
						$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/" . $INFOFORM_PAGES[$type] . "?ID=" . $data['ID'] . "\">";
						$out .= $data["ITEM_$num"];
						if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
							$out .= " (" . $data["ID"] . ")";
						$out .= "</a>";
					}
					return "<center>".$out."</center>";
					break;

				// ** Hidden auth passphrase (SNMP v3)
				case "glpi_plugin_tracker_snmp_connection.auth_passphrase" :
					if (empty($data["ITEM_$num"]))
						$out = "";
					else
						$out = "********";
					return $out;
					break;

				// ** Hidden priv passphrase (SNMP v3)
				case "glpi_plugin_tracker_snmp_connection.priv_passphrase" :
					if (empty($data["ITEM_$num"]))
						$out = "";
					else
						$out = "********";
					return $out;
					break;
			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($field) {

				// Display name of unknown device
				case "glpi_plugin_tracker_unknown_device.name" :
					if (empty($data["ITEM_$num"]))
						$out = "<a href='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.unknown.form.php?ID=".$data["ID"]."'>(".$data["ID"].")</a>";
					else
						$out = "<a href='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.unknown.form.php?ID=".$data["ID"]."'>".$data["ITEM_$num"]."</a>";
					return "<center>".$out."</center>";
					break;

				// Display switch on witch unknown device is connected
				case "glpi_networking.ID" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					$netport = new Netport;
					$netport->getDeviceData($data["ITEM_$num"],NETWORKING_TYPE);

					$out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?ID=".$data["ITEM_$num"]."\">";
					$out .=  $netport->device_name;
					if ($CFG_GLPI["view_ID"]) $out .= " (".$data["ITEM_$num"].")";
					$out .=  "</a><br/>";
					return "<center>".$out."</center>";
					break;

				// ** Tracker - switch port
				case "glpi_networking_ports.name" :
					$out = '';
					include_once(GLPI_ROOT."/inc/networking.class.php");
					if (!empty($data["ITEM_$num"]))
					{
						$np = new Netport;
						$np->getFromDB($data["ITEM_$num"]);
						$out .= "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$np->fields["name"]."</a><br/>";
					}
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * 
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS :
			switch ($field) {

			}
			break;

		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_tracker_networking_ports.ID" :
					$query = "SELECT glpi_networking.name as name,glpi_networking.ID as ID FROM glpi_networking
					LEFT JOIN glpi_networking_ports ON on_device = glpi_networking.ID
					WHERE glpi_networking_ports.ID='".$data["ITEM_2"]."'
					LIMIT 0,1";
					$result = $DB->query($query);
					$data2 = $DB->fetch_assoc($result);
					$out = "<a href='".GLPI_ROOT."/front/networking.form.php?ID=".$data2["ID"]."'>".$data2["name"]."</a>";
				return "<center>".$out."</center>";
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_tracker_networking_ports.FK_networking_ports" :
					$netport=new Netport;
					$netport->getFromDB($data["ITEM_$num"]);
					$out = "<a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$netport->fields["name"]."</a>";
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
			switch ($field) {

				// ** Name of agent and link to form
				case "glpi_plugin_tracker_agents.name" :
					$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.form.php?ID=".$data['ID']."'>";
					$out .= $data["ITEM_$num"];
					if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
						$out .= " (" . $data["ID"] . ")";
					$out .="</a>";
					return "<center>".$out."</center>";
					break;

				// ** Display Yes/No of lock of agent
				case "glpi_plugin_tracker_agents.lock" :
					$out = getYesNo($data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

				// ** Display log activation / level
				case "glpi_plugin_tracker_agents.logs" :
					$ArrayValues[]= $LANG["choice"][0];
					$ArrayValues[]= $LANG["choice"][1];
					$ArrayValues[]= $LANG["setup"][137];
					$out = $ArrayValues[$data["ITEM_$num"]];
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($field) {

				// ** Name of range IP and link to form
				case "glpi_plugin_tracker_rangeip.name" :
					$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.rangeip.form.php?ID=".$data['ID']."'>";
					$out .= $data["ITEM_$num"];
					if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
						$out .= " (" . $data["ID"] . ")";
					$out .="</a>";
					return "<center>".$out."</center>";
					break;

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_tracker_agents.ID" :
					$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.php?ID=".$data["ITEM_$num"]."'>";
					$out .= getDropdownName("glpi_plugin_tracker_agents", $data["ITEM_$num"], 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** Display Yes/No discovery fonction
				case "glpi_plugin_tracker_rangeip.discover" :
					$out = getYesNo($data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

				// ** Display Yes/No query fonction
				case "glpi_plugin_tracker_rangeip.query" :
					$out = getYesNo($data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

				// ** Display entity name
				case "glpi_entities.name" :
					if ($data["ITEM_$num"] == '')
					{
						$out = getDropdownName("glpi_entities",$data["ITEM_$num"]);
						return "<center>".$out."</center>";
					}
					break;
				
			}
			break;

		// * Device discovery list (plugins/tracker/front/plugin_tracker.discovery.php)
		case PLUGIN_TRACKER_SNMP_DISCOVERY :
			switch ($field) {

				// ** Display type of device (networking, printer, computer...)
				case "glpi_plugin_tracker_discovery.type" :
					if ($data["ITEM_$num"] == "0")
						$out = "";
					else
					{
						$ci=new CommonItem();
						$ci->setType($data["ITEM_$num"]);
						$out=$ci->getType();
					}
					return "<center>".$out."</center>";
					break;

				// ** Display entity name
				case "glpi_entities.name" :
					if ($data["ITEM_$num"] == '')
					{
						$out = getDropdownName("glpi_entities",$data["ITEM_$num"]);
						return "<center>".$out."</center>";
					}
					break;

			}
			break;

		// * Processes agents list (plugins/tracker/front/plugin_tracker.agents.processes.php)
		case PLUGIN_TRACKER_AGENTS_PROCESSES :
			switch ($field) {

			// ** Agent name and link to form
			case "glpi_plugin_tracker_agents.ID" :
				$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.php?ID=".$data["ITEM_$num"]."'>";
				$out .= getDropdownName("glpi_plugin_tracker_agents", $data["ITEM_$num"], 0);
				$out .= "</a>";
				return "<center>".$out."</center>";
				break;

			// ** Display status of agent (finish or in progress)
			case "glpi_plugin_tracker_agents_processes.status" :
				$out = "";
				switch($data["ITEM_$num"])
				{
					case 3 :
						$out = "<img src='../pics/export.png' />";
						break;
					case 2 :
						$out = "<img src='../pics/wait.png' />";
						break;
					case 1 :
						$out = "<img src='../pics/ok2.png' />";
						break;
				}
				return "<center>".$out."</center>";
				break;

			// ** Display date and hour of finished agent execution
			case "glpi_plugin_tracker_agents_processes.end_time" :
				$out = $data["ITEM_$num"];
				if ($out == "0000-00-00 00:00:00")
					$out = "-";
				return "<center>".$out."</center>";
				break;

			// ** Counter of devices discovered
			case "glpi_plugin_tracker_agents_processes.discovery_queries" :
				$agents_processes = new plugin_tracker_agents_processes;
				$agents_processes->getFromDB($data['ID']);
				$out = $data["ITEM_$num"]." / ".$agents_processes->fields["discovery_queries_total"];
				if ($out == "0 / 0")
					$out = 0;
				return "<center>".$out."</center>";
				break;

			// ** Counter of devices queried
			case "glpi_plugin_tracker_agents_processes.networking_queries" :
				$agents_processes = new plugin_tracker_agents_processes;
				$agents_processes->getFromDB($data['ID']);
				$out = $data["ITEM_$num"] + $agents_processes->fields["printers_queries"];
				return "<center>".$out."</center>";
				break;
			
			// ** Total time of execution script
			case "glpi_plugin_tracker_agents_processes.ID" :
				$duree_timestamp = strtotime($data["ITEM_5"]) - strtotime($data["ITEM_4"]);
				$out = timestampToString($duree_timestamp);
				return "<center>".$out."</center>";
				break;

			// ** Total time of discovery function
			case "glpi_plugin_tracker_agents_processes.start_time_discovery" :
				$agents_processes = new plugin_tracker_agents_processes;
				$agents_processes->getFromDB($data['ID']);
				$duree_timestamp = strtotime($agents_processes->fields["end_time_discovery"]) - strtotime($data["ITEM_$num"]);
				$out = timestampToString($duree_timestamp);
				return "<center>".$out."</center>";
				break;

			// ** Total time of query function
			case "glpi_plugin_tracker_agents_processes.start_time_query" :
				$agents_processes = new plugin_tracker_agents_processes;
				$agents_processes->getFromDB($data['ID']);
				$duree_timestamp = strtotime($agents_processes->fields["end_time_query"]) - strtotime($data["ITEM_$num"]);
				$out = timestampToString($duree_timestamp);
				return "<center>".$out."</center>";
				$out = "-";
				return "<center>".$out."</center>";
				break;
				
			}
			break;

		// * Detail of ports history (plugins/tracker/report/plugin_tracker.switch_ports.history.php)
		case PLUGIN_TRACKER_SNMP_HISTORY :
			switch ($field) {

				// ** Display switch and Port
				case "glpi_networking_ports.ID" :
					$Array_device = plugin_tracker_getUniqueObjectfieldsByportID($data["ITEM_$num"]);
					$CommonItem = new CommonItem;
					$CommonItem->getFromDB($Array_device["device_type"], $Array_device["on_device"]);
					$out = "<div align='center'>" . $CommonItem->getLink(1);

					$query = "SELECT * FROM glpi_networking_ports
					WHERE ID='" . $data["ITEM_$num"] . "' ";
					$result = $DB->query($query);

					if ($DB->numrows($result) != "0")
						$out .= "<br/><a href='".GLPI_ROOT."/front/networking.port.php?ID=".$data["ITEM_$num"]."'>".$DB->result($result, 0, "name")."</a>";

					$out .= "</td>";
					return $out;
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_tracker_snmp_history.Field" :
					if ($data["ITEM_$num"] == "0")
					{
						if (empty($data["ITEM_4"]))
							return "<center><b>".$LANGTRACKER["history"][3]."</b></center>";
						else if (empty($data["ITEM_5"]))
							return "<center><b>".$LANGTRACKER["history"][2]."</b></center>";
					}
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_tracker_snmp_history.old_value" :
					// TODO ADD LINK TO DEVICE
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"])))
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_tracker_snmp_history.new_value" :
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"])))
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
					break;

			}
			break;

	}

	if (($type == PLUGIN_TRACKER_SNMP_AGENTS) AND ($linkfield == "EXPORT")) {
		$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.agents.export.php' target='_blank'>
					<input type='hidden' name='agent' value='" . $data["ID"] . "' />
					<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
					</form></div>";
		return $out;
	}
	return "<center>".$data["ITEM_$num"]."</center>";
}
// Define Dropdown tables to be manage in GLPI :
function plugin_tracker_getDropdown()
{
	// Table => Name
	global $LANG;
	if (isset ($_SESSION["glpi_plugin_tracker_installed"]) && $_SESSION["glpi_plugin_tracker_installed"] == 1)
		return array (
			"glpi_dropdown_plugin_tracker_snmp_version" => "SNMP version",
			"glpi_dropdown_plugin_tracker_mib_oid" => "OID MIB",
			"glpi_dropdown_plugin_tracker_mib_object" => "Objet MIB",
			"glpi_dropdown_plugin_tracker_mib_label" => "Label MIB"
		);
	else
		return array ();

}

/* Cron */
function cron_plugin_tracker() {
	//plugin_tracker_printingCounters();
	//plugin_tracker_cleaningHistory();
	$plugin_tracker_unknown = new plugin_tracker_unknown;
	$plugin_tracker_unknown->FusionUnknownKnownDevice();
}

// Define headings added by the plugin //
function plugin_get_headings_tracker($type,$withtemplate){
	global $LANG,$LANGTRACKER;
	$config = new plugin_tracker_config();	

	if (in_array($type,array(NETWORKING_TYPE))){
		// template case
		if ($withtemplate)
			return array();
		// Non template case
		else {
			if ((plugin_tracker_haveRight("snmp_networking", "r")) AND ($config->getValue("activation_snmp_networking") == "1")) {
				return array(
					1 => $LANGTRACKER["title"][1]
				);
			}
		}
	}else if (in_array($type,array(PRINTER_TYPE))){
		// template case
		if ($withtemplate)
			return array();
		// Non template case
		else {
				if ((plugin_tracker_haveRight("snmp_printers", "r")) AND ($config->getValue("activation_snmp_printer") == "1")) {
				return array(
					1 => $LANGTRACKER["title"][1]
				);
			}
		}
	}else	if (in_array($type,array(PROFILE_TYPE))){
		// template case
		if ($withtemplate)
			return array();
		// Non template case
		else 
			return array(
					1 => $LANGTRACKER["title"][1],
					);
	}else
		return false;	
}
/*
function plugin_get_headings_tracker($type, $withtemplate) {

	global $LANG;
	$config = new plugin_tracker_config();

	switch ($type) {

		case COMPUTER_TYPE :
			$array = array();
			// template case
			if ($withtemplate)
				return array ();
			// Non template case
			else {
				$array = array ();

				if (plugin_tracker_haveRight("printers_info", "r")) {
					$array = array (
						2 => $LANGTRACKER["title"][1]
					);
				}
				if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
					$array = array (
						1 => $LANGTRACKER["title"][2]
					);
				}
				return $array;
			}

			break;

		case USER_TYPE :
			$array = array();
			// template case
			if ($withtemplate)
				return array ();
			// Non template case
			else {
				if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
					return array (
						1 => $LANGTRACKER["title"][2]
					);
				}
			}

			break;

	}
	return false;
}
*/

// Define headings actions added by the plugin	 
function plugin_headings_actions_tracker($type) {

	$config = new plugin_tracker_config();

	switch ($type) {
		case COMPUTER_TYPE :

			$array = array ();
			if (plugin_tracker_haveRight("printers_info", "r")) {
				$array = array (
					2 => "plugin_headings_tracker_computersInfo"
				);
			}
			if ((plugin_tracker_haveRight("computers_history", "r")) && (($config->isActivated('computers_history')) == true)) {
				$array = array (
					1 => "plugin_headings_tracker_computerHistory"
				);
			}

			return $array;

			break;

		case PRINTER_TYPE :

			$array = array ();

			if (plugin_tracker_haveRight("snmp_printers", "r")) {
				$array = array (
					1 => "plugin_headings_tracker_printerInfo"
				);
			}

			return $array;

			break;

		case NETWORKING_TYPE :

			if (plugin_tracker_haveRight("snmp_networking", "r")) {
				$array = array (
					1 => "plugin_headings_tracker_networkingInfo"
				);
			}

			return $array;

			break;

		case USER_TYPE :

			break;
		case PROFILE_TYPE :
			return array(
				1 => "plugin_headings_tracker",
				);
			break;

	}
	return false;
}


function plugin_headings_tracker_computerHistory($type, $ID)
{
	$computer_history = new plugin_tracker_computers_history();
	$computer_history->showForm(COMPUTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerErrors($type, $ID)
{
	$errors = new plugin_tracker_errors();
	$errors->showForm(COMPUTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_computerInfo($type, $ID)
{
//	$plugin_tracker_printers = new plugin_tracker_printers();
//	$plugin_tracker_printers->showFormPrinter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerInfo($type, $ID)
{
	include_once(GLPI_ROOT."/inc/stat.function.php");
	$plugin_tracker_printers = new plugin_tracker_printers();
	$plugin_tracker_printers->showFormPrinter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $ID);
	$plugin_tracker_printers->showFormPrinter_pagescounter(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_info.form.php', $ID);
}

function plugin_headings_tracker_printerHistory($type, $ID)
{
	$print_history = new plugin_tracker_printers_history();
	$print_history->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_history.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerErrors($type, $ID)
{
	$errors = new plugin_tracker_errors();
	$errors->showForm(PRINTER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_printerCronConfig($type, $ID)
{
	$print_config = new plugin_tracker_printers_history_config();
	$print_config->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.printer_history_config.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_networkingInfo($type, $ID)
{
	$snmp = new plugin_tracker_networking();
	$snmp->showForm(GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.switch_info.form.php', $ID);
}

function plugin_headings_tracker_networkingErrors($type, $ID)
{
	$errors = new plugin_tracker_errors();
	$errors->showForm(NETWORKING_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.errors.form.php', $_GET["ID"]);
}

function plugin_headings_tracker_userHistory($type, $ID)
{
	$computer_history = new plugin_tracker_computers_history();
	$computer_history->showForm(USER_TYPE, GLPI_ROOT . '/plugins/tracker/front/plugin_tracker.computer_history.form.php', $_GET["ID"]);
}


function plugin_headings_tracker($type,$ID,$withtemplate=0)
{
	global $CFG_GLPI;

	switch ($type){
		case PROFILE_TYPE :
			$prof=new plugin_tracker_Profile();	
			if (!$prof->GetfromDB($ID))
				plugin_tracker_createaccess($ID);				
			$prof->showForm($CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.profile.php",$ID);		
		break;
	}
}


function plugin_tracker_MassiveActions($type)
{
	global $LANG,$LANGTRACKER;
	switch ($type) {
		case NETWORKING_TYPE :
			return array (
				"plugin_tracker_assign_model" => $LANGTRACKER["massiveaction"][1],
				"plugin_tracker_assign_auth" => $LANGTRACKER["massiveaction"][2]
			);
			break;
		case PRINTER_TYPE :
			return array (
				"plugin_tracker_assign_model" => $LANGTRACKER["massiveaction"][1],
				"plugin_tracker_assign_auth" => $LANGTRACKER["massiveaction"][2]
			);
			break;
		case PLUGIN_TRACKER_SNMP_DISCOVERY;
			return array (
				"plugin_tracker_discovery_import" => $LANG["buttons"][37]
			);
	}
	return array ();
}

function plugin_tracker_MassiveActionsDisplay($type, $action)
{

	global $LANG, $CFG_GLPI, $DB;
	switch ($type) {
		case NETWORKING_TYPE :
			switch ($action) {
				case "plugin_tracker_assign_model" :
					dropdownValue("glpi_plugin_tracker_model_infos", "snmp_model", "name");
					echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
					break;
				case "plugin_tracker_assign_auth" :
					plugin_tracker_snmp_auth_dropdown();
					echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
					break;
			}
			break;
		case PRINTER_TYPE :
			switch ($action) {
				case "plugin_tracker_assign_model" :
					dropdownValue("glpi_plugin_tracker_model_infos", "snmp_model", "name");
					echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
					break;
				case "plugin_tracker_assign_auth" :
					plugin_tracker_snmp_auth_dropdown();
					echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
					break;
			}
			break;
		case PLUGIN_TRACKER_SNMP_DISCOVERY;
			switch ($action) {
				case "plugin_tracker_discovery_import" :
					echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
					break;
			}
			break;
	}
	return "";
}

function plugin_tracker_MassiveActionsProcess($data)
{
	global $LANG;
	switch ($data['action']) {
		case "plugin_tracker_assign_model" :
			if ($data['device_type'] == NETWORKING_TYPE) {
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, NETWORKING_TYPE, "model", $data["snmp_model"]);
					}

				}
			}
			else if($data['device_type'] == PRINTER_TYPE)
			{
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
			}
			else if($data['device_type'] == PRINTER_TYPE)
			{
				foreach ($data['item'] as $key => $val) {
					if ($val == 1) {
						plugin_tracker_assign($key, PRINTER_TYPE, "auth", $data["FK_snmp_connection"]);
					}

				}
			}
			break;
		case "plugin_tracker_discovery_import" :
			foreach ($data['item'] as $key => $val) {
				if ($val == 1) {
					plugin_tracker_discovery_import($key);
				}
			}
			break;
	}
}

// How to display specific update fields ?
// Massive Action functions
function plugin_tracker_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield){
	global $LINK_ID_TABLE,$LANG;
	// Table fields
	//echo $table.".".$field."<br/>";
	switch ($table.".".$field){
		case 'glpi_entities.name':
			dropdownValue("glpi_entities",$linkfield);
			return true;
			break;
		case 'glpi_plugin_tracker_snmp_connection.name':
			dropdownValue("glpi_plugin_tracker_snmp_connection",$linkfield);
			return true;
			break;
		case 'glpi_plugin_tracker_model_infos.name':
			dropdownValue("glpi_plugin_tracker_model_infos",$linkfield,'',0);
			return true;
			break;
		case 'glpi_plugin_tracker_discovery.type' :
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			dropdownDeviceTypes('type',$linkfield,$type_list);
			return true;
			break;
		case 'glpi_plugin_tracker_rangeip.discover' :
			dropdownYesNo('discover',$linkfield);
			return true;
			break;
		case 'glpi_plugin_tracker_rangeip.query' :
			dropdownYesNo('query',$linkfield);
			return true;
			break;
		case 'glpi_plugin_tracker_rangeip.FK_tracker_agents' :
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
		case 'glpi_plugin_tracker_agents.lock' :
			dropdownYesNo('lock',$linkfield);
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
		case 'glpi_plugin_tracker_agents.last_agent_update' :
			return true;
			break;
		case 'glpi_plugin_tracker_agents.tracker_agent_version' :
			return true;
			break;
		case 'glpi_plugin_tracker_agents.ID' :
			return true;
			break;
		case 'glpi_plugin_tracker_discovery.FK_snmp_connection' :
			$plugin_tracker_snmp = new plugin_tracker_snmp_auth;
			echo $plugin_tracker_snmp->selectbox();
			return true;
			break;
		case 'glpi_plugin_tracker_model_infos.ID' :
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
		case 'glpi_plugin_tracker_model_infos.activation' :
			return true;
			break;
		case 'glpi_plugin_tracker_model_infos.discovery_key' :
			return true;
			break;
	}
	return false;
	
}


function plugin_tracker_addSelect($type,$ID,$num){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

			// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
					return "TRACKER_13.".$field." AS ITEM_$num, ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					return "TRACKER_22.".$field." AS ITEM_$num, ";
					break;
			}
			break;

		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				case "glpi_networking.ID" :
					return "TRACKER_13.".$field." AS ITEM_$num, ";
					break;

				case "glpi_networking_ports.name" :
					return "TRACKER_22.ID AS ITEM_$num, ";
					break;

			}
			break;
	}
	return "";
}




// * Search modification for plugin Tracker

function plugin_tracker_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables){

//	echo "Left Join : ".$new_table.".".$linkfield."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** Tracker - switch
				case "glpi_plugin_tracker_networking.ID" :
/*					return " LEFT JOIN glpi_plugin_tracker_networking_ports AS TRACKER_10 ON (glpi_computers.ID = TRACKER_10.ID) ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_11 ON TRACKER_11.on_device = glpi_computers.ID AND TRACKER_11.device_type='".COMPUTER_TYPE."' ".
						" LEFT JOIN glpi_networking_wire AS TRACKER_12 ON TRACKER_11.ID = TRACKER_12.end1 OR  TRACKER_11.ID = TRACKER_12.end2 ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_13 ON TRACKER_13.ID = (TRACKER_12.end1, TRACKER_12.end2) NOT IN (SELECT end1, end2 FROM glpi_networking_wire WHERE (end1 != TRACKER_11.ID OR end2 != TRACKER_11.ID) ) ".
						" LEFT JOIN glpi_networking AS TRACKER_14 ON TRACKER_13.on_device = TRACKER_14.ID";
*/				//		" LEFT JOIN glpi_plugin_tracker_networking AS TRACKER_15 ON TRACKER_14.ID = TRACKER_15.FK_networking";
					return " LEFT JOIN glpi_networking_ports AS TRACKER_10 ON (TRACKER_10.on_device = glpi_computers.ID AND TRACKER_10.device_type='".COMPUTER_TYPE."') ".
						" LEFT JOIN glpi_networking_wire AS TRACKER_11 ON TRACKER_10.ID = TRACKER_11.end1 OR TRACKER_10.ID = TRACKER_11.end2 ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = TRACKER_10.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END ".
						" LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device = TRACKER_13.ID";

					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
//					return " LEFT JOIN glpi_plugin_tracker_networking_ports AS TRACKER_20 ON (glpi_computers.ID = TRACKER_20.ID) ".
//						" LEFT JOIN glpi_networking_ports AS TRACKER_21 ON TRACKER_21.on_device = glpi_computers.ID AND TRACKER_21.device_type='".COMPUTER_TYPE."' ".
//						" LEFT JOIN glpi_networking_wire AS TRACKER_22 ON TRACKER_21.ID = TRACKER_22.end1 OR TRACKER_21.ID = TRACKER_22.end2 ".
//						" LEFT JOIN glpi_networking_ports AS TRACKER_23 ON TRACKER_23.ID = (TRACKER_22.end1, TRACKER_22.end2) NOT IN (SELECT end1, end2 FROM glpi_networking_wire WHERE (end1 != TRACKER_21.ID OR end2 != TRACKER_21.ID) ) ";
					return " LEFT JOIN glpi_networking_ports AS TRACKER_20 ON (TRACKER_20.on_device = glpi_computers.ID AND TRACKER_20.device_type='".COMPUTER_TYPE."') ".
						" LEFT JOIN glpi_networking_wire AS TRACKER_21 ON TRACKER_20.ID = TRACKER_21.end1 OR TRACKER_20.ID = TRACKER_21.end2 ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = TRACKER_20.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";

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

			}
			break;

		// * Model List (plugins/tracker/front/plugin_tracker.models.php)
		case PLUGIN_TRACKER_MODEL :
			switch ($new_table.".".$linkfield) {

				// ** Name of model and link to form

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_tracker_model_infos.device_type" :
					break;

				// ** Display pic / link for exporting model

				// ** Display yes/no activation of model

			}
			break;

		// * Authentification List (plugins/tracker/front/plugin_tracker.snmp_auth.php)
		case PLUGIN_TRACKER_SNMP_AUTH :
			switch ($new_table.".".$linkfield) {

				// ** Name of authentification and link to form

				// ** Hidden auth passphrase (SNMP v3)

				// ** Hidden priv passphrase (SNMP v3)

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($new_table.".".$linkfield) {

				// ** Tracker - switch
				case "glpi_networking.ID" :
					return " LEFT JOIN glpi_networking_ports AS TRACKER_10 ON (TRACKER_10.on_device = glpi_plugin_tracker_unknown_device.ID AND TRACKER_10.device_type='".PLUGIN_TRACKER_MAC_UNKNOWN."') ".
						" LEFT JOIN glpi_networking_wire AS TRACKER_11 ON TRACKER_10.ID = TRACKER_11.end1 OR TRACKER_10.ID = TRACKER_11.end2 ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_12 ON TRACKER_12.ID = CASE WHEN TRACKER_11.end1 = TRACKER_10.ID THEN TRACKER_11.end2 ELSE TRACKER_11.end1 END ".
						" LEFT JOIN glpi_networking AS TRACKER_13 ON TRACKER_12.on_device = TRACKER_13.ID";

					break;

				// ** Tracker - switch port
				case "glpi_networking_ports.name" :
					return " LEFT JOIN glpi_networking_ports AS TRACKER_20 ON (TRACKER_20.on_device = glpi_plugin_tracker_unknown_device.ID AND TRACKER_20.device_type='".PLUGIN_TRACKER_MAC_UNKNOWN."') ".
						" LEFT JOIN glpi_networking_wire AS TRACKER_21 ON TRACKER_20.ID = TRACKER_21.end1 OR TRACKER_20.ID = TRACKER_21.end2 ".
						" LEFT JOIN glpi_networking_ports AS TRACKER_22 ON TRACKER_22.ID = CASE WHEN TRACKER_21.end1 = TRACKER_20.ID THEN TRACKER_21.end2 ELSE TRACKER_21.end1 END ";
					break;

			}
			break;

		// * 
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS :
			switch ($new_table.".".$linkfield) {

			}
			break;

		// * Ports date connection - report (plugins/tracker/report/plugin_tracker.ports_date_connections.php)
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS2 :
			switch ($new_table.".".$linkfield) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_tracker_networking_ports.ID" :

				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_tracker_networking_ports.FK_networking_ports" :

					break;

				// ** Location of switch
				case "glpi_dropdown_locations.FK_networking_ports" :
					return " LEFT JOIN glpi_networking_ports ON (glpi_plugin_tracker_networking_ports.FK_networking_ports = glpi_networking_ports.ID) ".
						" LEFT JOIN glpi_networking ON glpi_networking_ports.on_device = glpi_networking.ID".
						" LEFT JOIN glpi_dropdown_locations ON glpi_dropdown_locations.ID = glpi_networking.location";
					break;

			}
			break;

		// * Tracker Agents list (plugins/tracker/front/plugin_tracker.agents.php)
		case PLUGIN_TRACKER_SNMP_AGENTS :
			switch ($new_table.".".$linkfield) {

				// ** Name of agent and link to form
				case "glpi_plugin_tracker_agents.name" :

					break;

				// ** Display Yes/No of lock of agent
				case "glpi_plugin_tracker_agents.lock" :

					break;

				// ** Display log activation / level
				case "glpi_plugin_tracker_agents.logs" :

					break;

			}
			break;

		// * range IP list (plugins/tracker/front/plugin_tracker.rangeip.php)
		case PLUGIN_TRACKER_SNMP_RANGEIP :
			switch ($new_table.".".$linkfield) {

				// ** Name of range IP and link to form
				case "glpi_plugin_tracker_rangeip.name" :

					break;

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_tracker_agents.FK_tracker_agents" :
					return " LEFT JOIN glpi_plugin_tracker_agents ON (glpi_plugin_tracker_agents.ID = glpi_plugin_tracker_rangeip.FK_tracker_agents) ";
					break;

				// ** Display Yes/No discovery fonction
				case "glpi_plugin_tracker_rangeip.discover" :

					break;

				// ** Display Yes/No query fonction
				case "glpi_plugin_tracker_rangeip.query" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Device discovery list (plugins/tracker/front/plugin_tracker.discovery.php)
		case PLUGIN_TRACKER_SNMP_DISCOVERY :
			switch ($new_table.".".$linkfield) {

				// ** Display type of device (networking, printer, computer...)
				case "glpi_plugin_tracker_discovery.type" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Processes agents list (plugins/tracker/front/plugin_tracker.agents.processes.php)
		case PLUGIN_TRACKER_AGENTS_PROCESSES :
			switch ($new_table.".".$linkfield) {
				
			// ** Agent name and link to form
			case "glpi_plugin_tracker_agents_processes.FK_agent" :

				break;

			// ** Display status of agent (finish or in progress)
			case "glpi_plugin_tracker_agents_processes.status" :

				break;

			// ** Display date and hour of finished agent execution
			case "glpi_plugin_tracker_agents_processes.end_time" :

				break;

			// ** Counter of devices discovered
			case "glpi_plugin_tracker_agents_processes.discovery_queries" :

				break;

			// ** Counter of devices queried
			case "glpi_plugin_tracker_agents_processes.networking_queries" :

				break;

			// ** Total time of execution script
			case "glpi_plugin_tracker_agents_processes.ID" :

				break;

			// ** Total time of discovery function
			case "glpi_plugin_tracker_agents_processes.start_time_discovery" :

				break;

			// ** Total time of query function
			case "glpi_plugin_tracker_agents_processes.start_time_query" :

				break;

			}
			break;

		// * Detail of ports history (plugins/tracker/report/plugin_tracker.switch_ports.history.php)
		case PLUGIN_TRACKER_SNMP_HISTORY :
			switch ($new_table.".".$linkfield) {

				// ** Display switch and Port
				case "glpi_networking_ports.FK_ports" :
					return " LEFT JOIN glpi_networking_ports ON (glpi_plugin_tracker_snmp_history.FK_ports = glpi_networking_ports.ID) ".
						" LEFT JOIN glpi_networking ON glpi_networking_ports.on_device = glpi_networking.ID";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_tracker_snmp_history.Field" :

					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_tracker_snmp_history.old_value" :

					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_tracker_snmp_history.new_value" :

					break;

			}
	}
	return "";
}



function plugin_tracker_addOrderBy($type,$ID,$order,$key=0){
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
					return " ORDER BY TRACKER_13.name $order ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					return " ORDER BY TRACKER_22.name $order ";
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

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_networking.name" :
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

			}
			break;

		// * Model List (plugins/tracker/front/plugin_tracker.models.php)
		case PLUGIN_TRACKER_MODEL :
			switch ($table.".".$field) {

				// ** Name of model and link to form

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_tracker_model_infos.device_type" :
					break;

				// ** Display pic / link for exporting model

				// ** Display yes/no activation of model

			}
			break;

		// * Authentification List (plugins/tracker/front/plugin_tracker.snmp_auth.php)
		case PLUGIN_TRACKER_SNMP_AUTH :
			switch ($table.".".$field) {

				// ** Name of authentification and link to form

				// ** Hidden auth passphrase (SNMP v3)

				// ** Hidden priv passphrase (SNMP v3)

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** Tracker - switch
				case "glpi_networking.ID" :
					return " ORDER BY TRACKER_13.name $order ";
					break;

				// ** Tracker - switch port
				case "glpi_networking_ports.name" :
					return " ORDER BY TRACKER_22.name $order ";
					break;
				
			}
			break;

		// *
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS :
			switch ($table.".".$field) {

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
					return " ORDER BY glpi_dropdown_locations.name $order ";
					break;

			}
			break;

		// * Tracker Agents list (plugins/tracker/front/plugin_tracker.agents.php)
		case PLUGIN_TRACKER_SNMP_AGENTS :
			switch ($table.".".$field) {

				// ** Name of agent and link to form
				case "glpi_plugin_tracker_agents.name" :

					break;

				// ** Display Yes/No of lock of agent
				case "glpi_plugin_tracker_agents.lock" :

					break;

				// ** Display log activation / level
				case "glpi_plugin_tracker_agents.logs" :

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
					return " ORDER BY glpi_plugin_tracker_agents.name $order ";
					break;

				// ** Display Yes/No discovery fonction
				case "glpi_plugin_tracker_rangeip.discover" :

					break;

				// ** Display Yes/No query fonction
				case "glpi_plugin_tracker_rangeip.query" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Device discovery list (plugins/tracker/front/plugin_tracker.discovery.php)
		case PLUGIN_TRACKER_SNMP_DISCOVERY :
			switch ($table.".".$field) {

				// ** Display type of device (networking, printer, computer...)
				case "glpi_plugin_tracker_discovery.type" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Processes agents list (plugins/tracker/front/plugin_tracker.agents.processes.php)
		case PLUGIN_TRACKER_AGENTS_PROCESSES :
			switch ($table.".".$field) {

			// ** Agent name and link to form
			case "glpi_plugin_tracker_agents.ID" :
				return " ORDER BY glpi_plugin_tracker_agents.name $order ";
				break;

			// ** Display status of agent (finish or in progress)
			case "glpi_plugin_tracker_agents_processes.status" :

				break;

			// ** Display date and hour of finished agent execution
			case "glpi_plugin_tracker_agents_processes.end_time" :

				break;

			// ** Counter of devices discovered
			case "glpi_plugin_tracker_agents_processes.discovery_queries" :

				break;

			// ** Counter of devices queried
			case "glpi_plugin_tracker_agents_processes.networking_queries" :

				break;

			// ** Total time of execution script
			case "glpi_plugin_tracker_agents_processes.ID" :

				break;

			// ** Total time of discovery function
			case "glpi_plugin_tracker_agents_processes.start_time_discovery" :

				break;

			// ** Total time of query function
			case "glpi_plugin_tracker_agents_processes.start_time_query" :

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



function plugin_tracker_addWhere($link,$nott,$type,$ID,$val){ // Delete in 0.72
	global $SEARCH_OPTION,$TRACKER_MAPPING;

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
						$ADD=" OR TRACKER_13.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR TRACKER_13.name IS NOT NULL";
					}
					return $link." (TRACKER_13.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - switch port
				case "glpi_plugin_tracker_networking_ports.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR TRACKER_22.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
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
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NOT NULL";
					}
					return $link." ($table.last_tracker_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_networking.FK_snmp_connection" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_tracker_snmp_connection.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_tracker_snmp_connection.name IS NOT NULL";
					}
					return $link." (glpi_plugin_tracker_snmp_connection.name  LIKE '%".$val."%' $ADD ) ";
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
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_tracker_update IS NOT NULL";
					}
					return $link." ($table.last_tracker_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP models
				case "glpi_plugin_tracker_model_infos.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - SNMP authentification
				case "glpi_plugin_tracker_snmp_connection.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}
			break;

		// * Model List (plugins/tracker/front/plugin_tracker.models.php)
		case PLUGIN_TRACKER_MODEL :
			switch ($table.".".$field) {

				// ** Name of model and link to form

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_tracker_model_infos.device_type" :
//					Probleme, add select with REPLACE 
//
//					switch ($data["ITEM_$num"])
//					{
//						case COMPUTER_TYPE:
//							$out .= $LANG["Menu"][0];
//							break;
//						case NETWORKING_TYPE:
//							$out .= $LANG["Menu"][1];
//							break;
//						case PRINTER_TYPE:
//							$out .= $LANG["Menu"][2];
//							break;
//						case PERIPHERAL_TYPE:
//							$out .= $LANG["Menu"][16];
//							break;
//						case PHONE_TYPE:
//							$out .= $LANG["Menu"][34];
//
//
//					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Display pic / link for exporting model

				// ** Display yes/no activation of model

			}
			break;

		// * Authentification List (plugins/tracker/front/plugin_tracker.snmp_auth.php)
		case PLUGIN_TRACKER_SNMP_AUTH :
			switch ($table.".".$field) {

				// ** Name of authentification and link to form

				// ** Hidden auth passphrase (SNMP v3)

				// ** Hidden priv passphrase (SNMP v3)

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/tracker/report/plugin_tracker.unknown_mac.php)
		case PLUGIN_TRACKER_MAC_UNKNOWN :
			switch ($table.".".$field) {

				// ** Tracker - switch
				case "glpi_networking.ID" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR TRACKER_13.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR TRACKER_13.name IS NOT NULL";
					}
					return $link." (TRACKER_13.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Tracker - switch port
				case "glpi_networking_ports.name" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR TRACKER_22.name IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR TRACKER_22.name IS NOT NULL";
					}
					return $link." (TRACKER_22.name  LIKE '%".$val."%' $ADD ) ";
					break;
			}
			break;

		// *
		case PLUGIN_TRACKER_SNMP_NETWORKING_PORTS :
			switch ($table.".".$field) {

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
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_networking.location IS NOT NULL";
					}
					if ($val == "0")
						return $link." (glpi_networking.location >= -1 ) ";
					return $link." (glpi_networking.location = '".$val."' $ADD ) ";
					break;

				case "glpi_plugin_tracker_networking_ports.lastup" :
					$ADD = "";
					//$val = str_replace("&lt;",">",$val);
					//$val = str_replace("\\","",$val);
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NULL";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NOT NULL";
					}
					return $link." ($table.$field $val $ADD ) ";
					break;
			}
			break;

		// * Tracker Agents list (plugins/tracker/front/plugin_tracker.agents.php)
		case PLUGIN_TRACKER_SNMP_AGENTS :
			switch ($table.".".$field) {


			}
			break;

				// ** Name of agent and link to form
				case "glpi_plugin_tracker_agents.name" :

					break;

				// ** Display Yes/No of lock of agent
				case "glpi_plugin_tracker_agents.lock" :

					break;

				// ** Display log activation / level
				case "glpi_plugin_tracker_agents.logs" :

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
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** Display Yes/No discovery fonction
				case "glpi_plugin_tracker_rangeip.discover" :

					break;

				// ** Display Yes/No query fonction
				case "glpi_plugin_tracker_rangeip.query" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Device discovery list (plugins/tracker/front/plugin_tracker.discovery.php)
		case PLUGIN_TRACKER_SNMP_DISCOVERY :
			switch ($table.".".$field) {

				// ** Display type of device (networking, printer, computer...)
				case "glpi_plugin_tracker_discovery.type" :

					break;

				// ** Display entity name
				case "glpi_entities.name" :

					break;

			}
			break;

		// * Processes agents list (plugins/tracker/front/plugin_tracker.agents.processes.php)
		case PLUGIN_TRACKER_AGENTS_PROCESSES :
			switch ($table.".".$field) {

				// ** Agent name and link to form
				case "glpi_plugin_tracker_agents_processes.FK_agent" :

					break;

				// ** Display status of agent (finish or in progress)
				case "glpi_plugin_tracker_agents_processes.status" :

					break;

				// ** Display date and hour of finished agent execution
				case "glpi_plugin_tracker_agents_processes.end_time" :

					break;

				// ** Counter of devices discovered
				case "glpi_plugin_tracker_agents_processes.discovery_queries" :

					break;

				// ** Counter of devices queried
				case "glpi_plugin_tracker_agents_processes.networking_queries" :

					break;

				// ** Total time of execution script
				case "glpi_plugin_tracker_agents_processes.ID" :

					break;

				// ** Total time of discovery function
				case "glpi_plugin_tracker_agents_processes.start_time_discovery" :

					break;

				// ** Total time of query function
				case "glpi_plugin_tracker_agents_processes.start_time_query" :

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
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.ID IS NOT NULL ";
					}
					return $link." ($table.ID = '".$val."' $ADD ) ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_tracker_snmp_history.Field" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NULL ";
					}elseif ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.$field IS NOT NULL ";
					}
					if (!empty($val))
						$val = $TRACKER_MAPPING[NETWORKING_TYPE][$val]['name'];
					return $link." ($table.$field = '".addslashes($val)."' $ADD ) ";
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_tracker_snmp_history.old_value" :

					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_tracker_snmp_history.new_value" :

					break;

			}
	}
	return "";
}

function plugin_pre_item_purge_tracker($parm)
{
	global $DB;

	if (isset($parm["_item_type_"]))
		switch ($parm["_item_type_"]){
			case NETWORKING_TYPE :
				// Delete all ports
				$query_delete = "DELETE FROM glpi_plugin_tracker_networking
				WHERE FK_networking='".$parm["ID"]."' ";
				$DB->query($query_delete);

				$query_select = "SELECT glpi_plugin_tracker_networking_ports.ID FROM glpi_plugin_tracker_networking_ports
				LEFT JOIN glpi_networking_ports ON glpi_networking_ports.ID = FK_networking_ports
				WHERE on_device='".$parm["ID"]."'
					AND device_type='".NETWORKING_TYPE."'";
				$result=$DB->query($query_select);
				while ( $data=$DB->fetch_array($result) )
				{
					$query_delete = "DELETE FROM glpi_plugin_tracker_networking_ports
					WHERE ID='".$data["ID"]."'";
					$DB->query($query_delete);
				}

				break;
		}
	return $parm;
}


?>