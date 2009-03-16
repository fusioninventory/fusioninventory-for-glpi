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

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][1]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][1]['field'] = 'start_FK_processes';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][1]['linkfield'] = 'start_FK_processes';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][1]['name'] = $LANGTRACKER["processes"][15];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][2]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][2]['field'] = 'end_FK_processes';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][2]['linkfield'] = 'end_FK_processes';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][2]['name'] = $LANGTRACKER["processes"][16];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][3]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][3]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][3]['linkfield'] = 'ID';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][3]['name'] = $LANG["common"][2];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][4]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][4]['field'] = 'port';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][4]['linkfield'] = 'port';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][4]['name'] = $LANG["common"][1];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][5]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][5]['field'] = 'unknow_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][5]['linkfield'] = 'unknow_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][5]['name'] = $LANG["networking"][15];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][6]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][6]['field'] = 'start_time';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][6]['linkfield'] = 'start_time';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][6]['name'] = $LANGTRACKER["processes"][17];

	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][7]['table'] = 'glpi_plugin_tracker_unknown_mac';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][7]['field'] = 'end_time';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][7]['linkfield'] = 'end_time';
	$sopt[PLUGIN_TRACKER_MAC_UNKNOW][7]['name'] = $LANGTRACKER["processes"][18];

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
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['field'] = 'nb_process_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['linkfield'] = 'nb_process_query';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][3]['name'] = $LANGTRACKER["agents"][2];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['field'] = 'nb_process_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['linkfield'] = 'nb_process_discovery';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][4]['name'] = $LANGTRACKER["agents"][3];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['field'] = 'last_agent_update';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['linkfield'] = 'last_agent_update';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][5]['name'] = $LANGTRACKER["agents"][4];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['field'] = 'tracker_agent_version';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['linkfield'] = 'tracker_agent_version';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][6]['name'] = $LANGTRACKER["agents"][5];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['field'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['linkfield'] = 'lock';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][7]['name'] = $LANGTRACKER["agents"][6];
	
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['field'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['linkfield'] = 'logs';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][8]['name'] = $LANG["Menu"][30];

	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['table'] = 'glpi_plugin_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['field'] = 'ID';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['linkfield'] = 'EXPORT';
	$sopt[PLUGIN_TRACKER_SNMP_AGENTS][9]['name'] = $LANGTRACKER["agents"][7];

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

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['field'] = 'FK_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['linkfield'] = 'FK_tracker_agents';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][5]['name'] = $LANG["ocsng"][49];

	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['table'] = 'glpi_plugin_tracker_rangeip';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['field'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['linkfield'] = 'discover';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][6]['name'] = $LANGTRACKER["discovery"][3];
	
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['table'] = 'glpi_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['linkfield'] = 'FK_entities';
	$sopt[PLUGIN_TRACKER_SNMP_RANGEIP][7]['name'] = $LANG["entity"][0];


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

	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['table'] = 'glpi_plugin_tracker_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['field'] = 'name';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['linkfield'] = 'FK_snmp_connection';
	$sopt[PLUGIN_TRACKER_SNMP_DISCOVERY][10]['name'] = $LANGTRACKER["model_info"][3];


	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES]['common'] = $LANGTRACKER["errors"][0];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['field'] = 'process_number';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['linkfield'] = 'process_number';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][1]['name'] = $LANGTRACKER["processes"][15];

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][2]['field'] = 'FK_agent';
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

	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['table'] = 'glpi_plugin_tracker_agents_processes';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['field'] = 'networking_ports_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['linkfield'] = 'networking_ports_queries';
	$sopt[PLUGIN_TRACKER_AGENTS_PROCESSES][8]['name'] = $LANGTRACKER["processes"][8];	

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
	
	

	return $sopt;
}

//function plugin_tracker_giveItem($type,$ID,$data,$num)
function plugin_tracker_giveItem($type, $field, $data, $num, $linkfield = "")
{
//	global $CFG_GLPI, $DB, $INFOFORM_PAGES, $LINK_ID_TABLE,$LANG,$LANGTRACKER,$SEARCH_OPTION;
	global $CFG_GLPI, $LANG, $INFOFORM_PAGES, $LANGTRACKER, $DB;

//	$table=$SEARCH_OPTION[$type][$ID]["table"];
//	$field=$SEARCH_OPTION[$type][$ID]["field"];
//	$linkfield=$SEARCH_OPTION[$type][$ID]["linkfield"];

//	switch ($table.'.'.$field){
//echo $field."<br/>";
	switch ($field) {
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
		case "glpi_dropdown_plugin_tracker_snmp_version.FK_snmp_version" :
			$out = getDropdownName("glpi_dropdown_plugin_tracker_snmp_version", $data["ITEM_$num"], 0);
			return $out;
		case "glpi_plugin_tracker_snmp_connection.FK_snmp_connection" :
			$out = getDropdownName("glpi_plugin_tracker_snmp_connection", $data["ITEM_$num"], 0);
			return $out;
		case "glpi_plugin_tracker_snmp_connection.auth_passphrase" :
			if (empty($data["ITEM_$num"]))
				$out = "";
			else
				$out = "********";
			return $out;
		case "glpi_plugin_tracker_snmp_connection.priv_passphrase" :
			if (empty($data["ITEM_$num"]))
				$out = "";
			else
				$out = "********";
			return $out;
		case "glpi_plugin_tracker_errors.device_type" :
			switch ($data["ITEM_$num"]) {
				case COMPUTER_TYPE :
					$out = $LANGTRACKER["type"][1];
					break;
				case NETWORKING_TYPE :
					$out = $LANGTRACKER["type"][2];
					break;
				case PRINTER_TYPE :
					$out = $LANGTRACKER["type"][3];
					break;
			}
			return $out;
			break;
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

		case "glpi_plugin_tracker_errors.device_id" :
			$device_type = $data["ITEM_1"];
			$ID = $data["ITEM_$num"];
			$name = plugin_tracker_getDeviceFieldFromId($device_type, $ID, "name", NULL);

			$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/" . $INFOFORM_PAGES["$device_type"] . "?ID=" . $ID . "\">";
			$out .= $name;
			if (empty ($name) || $CFG_GLPI["view_ID"])
				$out .= " ($ID)";
			$out .= "</a>";
			return "<center>".$out."</center>";
			break;

		case "glpi_plugin_tracker_errors.first_pb_date" :
			$out = convDateTime($data["ITEM_$num"]);
			return $out;
			break;

		case "glpi_plugin_tracker_errors.last_pb_date" :
			$out = convDateTime($data["ITEM_$num"]);
			return $out;
			break;
		case "glpi_plugin_tracker_networking.FK_networking" :
			if ($num == "9") {
				$plugin_tracker_snmp = new plugin_tracker_snmp;
				$FK_model_DB = $plugin_tracker_snmp->GetSNMPModel($data["ID"],NETWORKING_TYPE);
				$out = "<div align='center'>" . getDropdownName("glpi_plugin_tracker_model_infos", $FK_model_DB, 0) . "</div>";
				return $out;
				break;
			} else
				if ($num == "10") {
					$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
					$FK_snmp_DB = $plugin_tracker_snmp_auth->GetInfos($data["ID"], GLPI_ROOT . "/plugins/tracker/scripts/",NETWORKING_TYPE);
					$out = "<div align='center'>" . $FK_snmp_DB["Name"] . "</div>";
					return $out;
					break;
				}
		case "glpi_plugin_tracker_unknown_mac.port" :
			$Array_device = getUniqueObjectfieldsByportID($data["ITEM_$num"]);
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
		case "glpi_plugin_tracker_agents.name" :
			$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.form.php?ID=".$data['ID']."'>";
			$out .= $data["ITEM_$num"];
			if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
				$out .= " (" . $data["ID"] . ")";
			$out .="</a>";
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_agents.lock" :
			$out = getYesNo($data["ITEM_$num"]);
			return "<center>".$out."</center>";
			break;			
		case "glpi_plugin_tracker_agents.logs" :
			$ArrayValues[]= $LANG["choice"][0];
			$ArrayValues[]= $LANG["choice"][1];
			$ArrayValues[]= $LANG["setup"][137];
			$out = $ArrayValues[$data["ITEM_$num"]];
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_rangeip.name" :
			$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.rangeip.form.php?ID=".$data['ID']."'>";
			$out .= $data["ITEM_$num"];
			if ($CFG_GLPI["view_ID"] || empty ($data["ITEM_$num"]))
				$out .= " (" . $data["ID"] . ")";
			$out .="</a>";
			return "<center>".$out."</center>";			break;
		case "glpi_plugin_tracker_rangeip.FK_tracker_agents" :
			$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.php?ID=".$data["ITEM_$num"]."'>";
			$out .= getDropdownName("glpi_plugin_tracker_agents", $data["ITEM_$num"], 0);
			$out .= "</a>";
			return "<center>".$out."</center>";
			break;	
		case "glpi_plugin_tracker_rangeip.discover" :
			$out = getYesNo($data["ITEM_$num"]);
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_discovery.type" :
			$ci=new CommonItem();
			$ci->setType($data["ITEM_$num"]);
			$out=$ci->getType();
			return "<center>".$out."</center>";
			break;
		case "glpi_entities.name" :
			if ($data["ITEM_$num"] == '')
			{
				$out = getDropdownName("glpi_entities",$data["ITEM_$num"]);
				return "<center>".$out."</center>";
			}
			break;
		case "glpi_plugin_tracker_agents_processes.FK_agent" :
			$out = "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.php?ID=".$data["ITEM_$num"]."'>";
			$out .= getDropdownName("glpi_plugin_tracker_agents", $data["ITEM_$num"], 0);
			$out .= "</a>";
			return "<center>".$out."</center>";
			break;
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
		case "glpi_plugin_tracker_agents_processes.end_time" :	
			$out = $data["ITEM_$num"];
			if ($out == "0000-00-00 00:00:00")
				$out = "-";
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_agents_processes.ID" :
			$duree_timestamp = strtotime($data["ITEM_5"]) - strtotime($data["ITEM_4"]);
			$out = timestampToString($duree_timestamp);
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_agents_processes.networking_queries" :
			$agents_processes = new plugin_tracker_agents_processes;
			$agents_processes->getFromDB($data['ID']);
			$out = $data["ITEM_$num"] + $agents_processes->fields["printers_queries"];
			return "<center>".$out."</center>";
			break;
			break;
		case "glpi_plugin_tracker_agents_processes.discovery_queries" :
			$agents_processes = new plugin_tracker_agents_processes;
			$agents_processes->getFromDB($data['ID']);
			$out = $data["ITEM_$num"]." / ".$agents_processes->fields["discovery_queries_total"];
			if ($out == "0 / 0")
				$out = 0;
			return "<center>".$out."</center>";
			break;
		case "glpi_plugin_tracker_agents_processes.start_time_discovery" :
			$agents_processes = new plugin_tracker_agents_processes;
			$agents_processes->getFromDB($data['ID']);
			$duree_timestamp = strtotime($agents_processes->fields["end_time_discovery"]) - strtotime($data["ITEM_$num"]);
			$out = timestampToString($duree_timestamp);
			return "<center>".$out."</center>";
			break;
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

	if (($type == PLUGIN_TRACKER_MODEL) AND ($linkfield == "EXPORT")) {
		$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/tracker/front/plugin_tracker.models.export.php' target='_blank'>
					<input type='hidden' name='model' value='" . $data["ID"] . "' />
					<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
					</form></div>";
		return $out;
	}
	else if (($type == PLUGIN_TRACKER_SNMP_AGENTS) AND ($linkfield == "EXPORT")) {
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

/* Cron for cleaning and printing counters */
function cron_plugin_tracker() {
	plugin_tracker_printingCounters();
	plugin_tracker_cleaningHistory();
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
	$print_config = new glpi_plugin_tracker_printers_history_config();
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
				"plugin_tracker" => $LANG["buttons"][37]
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
	}
}

// How to display specific update fields ?
// Massive Action functions
function plugin_tracker_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield){
	global $LINK_ID_TABLE,$LANG;
	// Table fields
//	echo $table.".".$field."<br/>";
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
		case 'glpi_plugin_tracker_rangeip.FK_tracker_agents' :
			dropdownValue("glpi_plugin_tracker_agents",$linkfield,'',0);
			return true;
			break;
		case 'glpi_plugin_tracker_agents.nb_process_query' :
			dropdownInteger("nb_process_query", $linkfield,1,200);
			return true;
		case 'glpi_plugin_tracker_agents.nb_process_discovery' :
			dropdownInteger("nb_process_discovery", $linkfield,1,400);
			return true;
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
	}
	return false;
	
}
?>