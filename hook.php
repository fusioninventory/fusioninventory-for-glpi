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
	$sopt['PluginFusioninventoryError']['common'] = $LANG['plugin_fusioninventory']["errors"][0];

	$sopt['PluginFusioninventoryError'][1]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][1]['field'] = 'ip';
	$sopt['PluginFusioninventoryError'][1]['linkfield'] = 'ip';
	$sopt['PluginFusioninventoryError'][1]['name'] = $LANG['plugin_fusioninventory']["errors"][1];

	$sopt['PluginFusioninventoryError'][30]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryError'][30]['linkfield'] = '';
	$sopt['PluginFusioninventoryError'][30]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryError'][3]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][3]['field'] = 'itemtype';
	$sopt['PluginFusioninventoryError'][3]['linkfield'] = 'itemtype';
	$sopt['PluginFusioninventoryError'][3]['name'] = $LANG["common"][1];

	$sopt['PluginFusioninventoryError'][4]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][4]['field'] = 'device_id';
	$sopt['PluginFusioninventoryError'][4]['linkfield'] = 'device_id';
	$sopt['PluginFusioninventoryError'][4]['name'] = $LANG["common"][16];

	$sopt['PluginFusioninventoryError'][6]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][6]['field'] = 'description';
	$sopt['PluginFusioninventoryError'][6]['linkfield'] = 'description';
	$sopt['PluginFusioninventoryError'][6]['name'] = $LANG['plugin_fusioninventory']["errors"][2];
  $sopt['PluginFusioninventoryError'][6]['datatype']='text';
  
	$sopt['PluginFusioninventoryError'][7]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][7]['field'] = 'first_pb_date';
	$sopt['PluginFusioninventoryError'][7]['linkfield'] = 'first_pb_date';
	$sopt['PluginFusioninventoryError'][7]['name'] = $LANG['plugin_fusioninventory']["errors"][3];
  $sopt['PluginFusioninventoryError'][7]['datatype']='datetime';
  
	$sopt['PluginFusioninventoryError'][8]['table'] = 'glpi_plugin_fusioninventory_errors';
	$sopt['PluginFusioninventoryError'][8]['field'] = 'last_pb_date';
	$sopt['PluginFusioninventoryError'][8]['linkfield'] = 'last_pb_date';
	$sopt['PluginFusioninventoryError'][8]['name'] = $LANG['plugin_fusioninventory']["errors"][4];
  $sopt['PluginFusioninventoryError'][8]['datatype']='datetime';
  
	$sopt['PluginFusioninventoryError'][80]['table'] = 'glpi_entities';
	$sopt['PluginFusioninventoryError'][80]['field'] = 'completename';
	$sopt['PluginFusioninventoryError'][80]['linkfield'] = 'entities_id';
	$sopt['PluginFusioninventoryError'][80]['name'] = $LANG["entity"][0];

	$sopt['PluginFusioninventoryModelinfo']['common'] = $LANG['plugin_fusioninventory']["profile"][19];

	$sopt['PluginFusioninventoryModelinfo'][1]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][1]['field'] = 'name';
	$sopt['PluginFusioninventoryModelinfo'][1]['linkfield'] = 'name';
	$sopt['PluginFusioninventoryModelinfo'][1]['name'] = $LANG["common"][16];
  $sopt['PluginFusioninventoryModelinfo'][1]['datatype']='itemlink';
  
	$sopt['PluginFusioninventoryModelinfo'][30]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryModelinfo'][30]['linkfield'] = '';
	$sopt['PluginFusioninventoryModelinfo'][30]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryModelinfo'][3]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][3]['field'] = 'itemtype';
	$sopt['PluginFusioninventoryModelinfo'][3]['linkfield'] = 'itemtype';
	$sopt['PluginFusioninventoryModelinfo'][3]['name'] = $LANG["common"][17];

	$sopt['PluginFusioninventoryModelinfo'][5]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][5]['field'] = 'id';
	$sopt['PluginFusioninventoryModelinfo'][5]['linkfield'] = '';
	$sopt['PluginFusioninventoryModelinfo'][5]['name'] = $LANG["buttons"][31];

	$sopt['PluginFusioninventoryModelinfo'][6]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][6]['field'] = 'activation';
	$sopt['PluginFusioninventoryModelinfo'][6]['linkfield'] = 'activation';
	$sopt['PluginFusioninventoryModelinfo'][6]['name'] = $LANG['plugin_fusioninventory']["model_info"][11];
	$sopt['PluginFusioninventoryModelinfo'][6]['datatype']='bool';

	$sopt['PluginFusioninventoryModelinfo'][7]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][7]['field'] = 'discovery_key';
	$sopt['PluginFusioninventoryModelinfo'][7]['linkfield'] = 'discovery_key';
	$sopt['PluginFusioninventoryModelinfo'][7]['name'] = $LANG['plugin_fusioninventory']["model_info"][12];

	$sopt['PluginFusioninventoryModelinfo'][8]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryModelinfo'][8]['field'] = 'comment';
	$sopt['PluginFusioninventoryModelinfo'][8]['linkfield'] = 'comment';
	$sopt['PluginFusioninventoryModelinfo'][8]['name'] = $LANG['common'][25];

	$sopt['PluginFusioninventoryConfigSNMPSecurity']['common'] = $LANG['plugin_fusioninventory']["profile"][22];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][1]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][1]['field'] = 'name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][1]['linkfield'] = 'name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][1]['name'] = $LANG["common"][16];
  $sopt['PluginFusioninventoryConfigSNMPSecurity'][1]['datatype']='itemlink';
  
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][30]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][30]['linkfield'] = 'id';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][30]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][3]['table'] = 'glpi_plugin_fusioninventory_snmpversions';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][3]['field'] = 'name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][3]['linkfield'] = 'plugin_fusioninventory_snmpversions_id';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][3]['name'] = $LANG['plugin_fusioninventory']["model_info"][2];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][4]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][4]['field'] = 'community';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][4]['linkfield'] = 'community';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][4]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][1];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][5]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][5]['field'] = 'sec_name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][5]['linkfield'] = 'sec_name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][5]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][2];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][7]['table'] = 'glpi_plugin_fusioninventory_snmpprotocolauths';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][7]['field'] = 'name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][7]['linkfield'] = 'auth_protocol';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][7]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][4];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][8]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][8]['field'] = 'auth_passphrase';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][8]['linkfield'] = 'auth_passphrase';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][8]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][5];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][9]['table'] = 'glpi_plugin_fusioninventory_snmpprotocolprivs';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][9]['field'] = 'name';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][9]['linkfield'] = 'priv_protocol';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][9]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][6];

	$sopt['PluginFusioninventoryConfigSNMPSecurity'][10]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][10]['field'] = 'priv_passphrase';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][10]['linkfield'] = 'priv_passphrase';
	$sopt['PluginFusioninventoryConfigSNMPSecurity'][10]['name'] = $LANG['plugin_fusioninventory']["snmpauth"][7];

	$sopt['PluginFusioninventoryUnknownDevice']['common'] = $LANG['plugin_fusioninventory']["menu"][4];

	$sopt['PluginFusioninventoryUnknownDevice'][1]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][1]['field'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][1]['linkfield'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][1]['name'] = $LANG["common"][16];
   $sopt['PluginFusioninventoryUnknownDevice'][1]['datatype']='itemlink';
   $sopt['PluginFusioninventoryUnknownDevice'][1]['forcegroupby']='1';

	$sopt['PluginFusioninventoryUnknownDevice'][2]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][2]['field'] = 'dnsname';
	$sopt['PluginFusioninventoryUnknownDevice'][2]['linkfield'] = 'dnsname';
	$sopt['PluginFusioninventoryUnknownDevice'][2]['name'] = $LANG['plugin_fusioninventory']["unknown"][0];

	$sopt['PluginFusioninventoryUnknownDevice'][3]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][3]['field'] = 'date_mod';
	$sopt['PluginFusioninventoryUnknownDevice'][3]['linkfield'] = '';
	$sopt['PluginFusioninventoryUnknownDevice'][3]['name'] = $LANG["common"][26];
   $sopt['PluginFusioninventoryUnknownDevice'][3]['datatype'] = 'datetime';

	$sopt['PluginFusioninventoryUnknownDevice'][4]['table'] = 'glpi_entities';
	$sopt['PluginFusioninventoryUnknownDevice'][4]['field'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][4]['linkfield'] = 'entities_id';
	$sopt['PluginFusioninventoryUnknownDevice'][4]['name'] = $LANG["entity"][0];

	$sopt['PluginFusioninventoryUnknownDevice'][5]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][5]['field'] = 'serial';
	$sopt['PluginFusioninventoryUnknownDevice'][5]['linkfield'] = 'serial';
	$sopt['PluginFusioninventoryUnknownDevice'][5]['name'] = $LANG['common'][19];

	$sopt['PluginFusioninventoryUnknownDevice'][6]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][6]['field'] = 'otherserial';
	$sopt['PluginFusioninventoryUnknownDevice'][6]['linkfield'] = 'otherserial';
	$sopt['PluginFusioninventoryUnknownDevice'][6]['name'] = $LANG['common'][20];

	$sopt['PluginFusioninventoryUnknownDevice'][7]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][7]['field'] = 'contact';
	$sopt['PluginFusioninventoryUnknownDevice'][7]['linkfield'] = 'contact';
	$sopt['PluginFusioninventoryUnknownDevice'][7]['name'] = $LANG['common'][18];
 
	$sopt['PluginFusioninventoryUnknownDevice'][8]['table'] = 'glpi_domains';
	$sopt['PluginFusioninventoryUnknownDevice'][8]['field'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][8]['linkfield'] = 'domain';
	$sopt['PluginFusioninventoryUnknownDevice'][8]['name'] = $LANG["setup"][89];

	$sopt['PluginFusioninventoryUnknownDevice'][9]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][9]['field'] = 'comment';
	$sopt['PluginFusioninventoryUnknownDevice'][9]['linkfield'] = 'comment';
	$sopt['PluginFusioninventoryUnknownDevice'][9]['name'] = $LANG['common'][25];

	$sopt['PluginFusioninventoryUnknownDevice'][10]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][10]['field'] = 'type';
	$sopt['PluginFusioninventoryUnknownDevice'][10]['linkfield'] = 'type';
	$sopt['PluginFusioninventoryUnknownDevice'][10]['name'] = $LANG['common'][17];

	$sopt['PluginFusioninventoryUnknownDevice'][11]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][11]['field'] = 'snmp';
	$sopt['PluginFusioninventoryUnknownDevice'][11]['linkfield'] = 'snmp';
	$sopt['PluginFusioninventoryUnknownDevice'][11]['name'] = $LANG['plugin_fusioninventory']["functionalities"][3];
   $sopt['PluginFusioninventoryUnknownDevice'][11]['datatype']='bool';
   
	$sopt['PluginFusioninventoryUnknownDevice'][12]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryUnknownDevice'][12]['field'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][12]['linkfield'] = 'plugin_fusioninventory_snmpmodels_id';
	$sopt['PluginFusioninventoryUnknownDevice'][12]['name'] = $LANG['plugin_fusioninventory']["model_info"][4];

   $sopt['PluginFusioninventoryUnknownDevice'][13]['table'] = 'glpi_plugin_fusioninventory_configsnmpsecurities';
   $sopt['PluginFusioninventoryUnknownDevice'][13]['field'] = 'name';
   $sopt['PluginFusioninventoryUnknownDevice'][13]['linkfield'] = 'plugin_fusioninventory_snmpauths_id';
   $sopt['PluginFusioninventoryUnknownDevice'][13]['name'] = $LANG['plugin_fusioninventory']["model_info"][3];

   $sopt['PluginFusioninventoryUnknownDevice'][14]['table'] = 'glpi_networkports';
   $sopt['PluginFusioninventoryUnknownDevice'][14]['field'] = 'ip';
   $sopt['PluginFusioninventoryUnknownDevice'][14]['linkfield'] = 'id';
   $sopt['PluginFusioninventoryUnknownDevice'][14]['name'] = $LANG["networking"][14];
   $sopt['PluginFusioninventoryUnknownDevice'][14]['forcegroupby']='1';

   $sopt['PluginFusioninventoryUnknownDevice'][15]['table'] = 'glpi_networkports';
   $sopt['PluginFusioninventoryUnknownDevice'][15]['field'] = 'mac';
   $sopt['PluginFusioninventoryUnknownDevice'][15]['linkfield'] = 'id';
   $sopt['PluginFusioninventoryUnknownDevice'][15]['name'] = $LANG["networking"][15];
   $sopt['PluginFusioninventoryUnknownDevice'][15]['forcegroupby']='1';

   $sopt['PluginFusioninventoryUnknownDevice'][16]['table'] = 'glpi_networkequipments';
   $sopt['PluginFusioninventoryUnknownDevice'][16]['field'] = 'device';
   $sopt['PluginFusioninventoryUnknownDevice'][16]['linkfield'] = 'device';
   $sopt['PluginFusioninventoryUnknownDevice'][16]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
   //$sopt['PluginFusioninventoryUnknownDevice'][16]['forcegroupby'] = '1';

   $sopt['PluginFusioninventoryUnknownDevice'][17]['table'] = 'glpi_plugin_fusioninventory_networkports';
   $sopt['PluginFusioninventoryUnknownDevice'][17]['field'] = 'id';
   $sopt['PluginFusioninventoryUnknownDevice'][17]['linkfield'] = 'id';
   $sopt['PluginFusioninventoryUnknownDevice'][17]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
   $sopt['PluginFusioninventoryUnknownDevice'][17]['forcegroupby'] = '1';

	$sopt['PluginFusioninventoryUnknownDevice'][18]['table'] = 'glpi_networkports';
	$sopt['PluginFusioninventoryUnknownDevice'][18]['field'] = 'name';
	$sopt['PluginFusioninventoryUnknownDevice'][18]['linkfield'] = 'id';
	$sopt['PluginFusioninventoryUnknownDevice'][18]['name'] = $LANG['plugin_fusioninventory']["unknown"][1];
   $sopt['PluginFusioninventoryUnknownDevice'][18]['forcegroupby']='1';

	$sopt['PluginFusioninventoryUnknownDevice'][19]['table'] = 'glpi_plugin_fusioninventory_unknowndevices';
	$sopt['PluginFusioninventoryUnknownDevice'][19]['field'] = 'accepted';
	$sopt['PluginFusioninventoryUnknownDevice'][19]['linkfield'] = 'accepted';
	$sopt['PluginFusioninventoryUnknownDevice'][19]['name'] = $LANG['plugin_fusioninventory']["unknown"][2];
   $sopt['PluginFusioninventoryUnknownDevice'][19]['datatype']='bool';

	$sopt['PluginFusioninventoryNetworkPort']['common'] = $LANG['plugin_fusioninventory']["errors"][0];

	$sopt['PluginFusioninventoryNetworkPort'][1]['name'] = $LANG["common"][16];

	$sopt['PluginFusioninventoryNetworkPort'][2]['name'] = $LANG['plugin_fusioninventory']["snmp"][42];

	$sopt['PluginFusioninventoryNetworkPort'][3]['name'] = $LANG['plugin_fusioninventory']["snmp"][43];

	$sopt['PluginFusioninventoryNetworkPort'][4]['name'] = $LANG['plugin_fusioninventory']["snmp"][44];

	$sopt['PluginFusioninventoryNetworkPort'][5]['name'] = $LANG['plugin_fusioninventory']["snmp"][45];

	$sopt['PluginFusioninventoryNetworkPort'][6]['name'] = $LANG['plugin_fusioninventory']["snmp"][46];

	$sopt['PluginFusioninventoryNetworkPort'][7]['name'] = $LANG['plugin_fusioninventory']["snmp"][47];

	$sopt['PluginFusioninventoryNetworkPort'][8]['name'] = $LANG['plugin_fusioninventory']["snmp"][48];

	$sopt['PluginFusioninventoryNetworkPort'][9]['name'] = $LANG['plugin_fusioninventory']["snmp"][49];

	$sopt['PluginFusioninventoryNetworkPort'][10]['name'] = $LANG['plugin_fusioninventory']["snmp"][51];

	$sopt['PluginFusioninventoryNetworkPort'][11]['name'] = $LANG['plugin_fusioninventory']["mapping"][115];

	$sopt['PluginFusioninventoryNetworkPort'][12]['name'] = $LANG["networking"][17];

	$sopt['PluginFusioninventoryNetworkPort'][13]['name'] = $LANG['plugin_fusioninventory']["snmp"][50];

	$sopt['PluginFusioninventoryNetworkPort'][14]['name'] = $LANG["networking"][56];

   $sopt['PluginFusioninventoryNetworkPort'][15]['name'] = $LANG['plugin_fusioninventory']["snmp"][41];

	$sopt['PluginFusioninventoryAgent']['common'] = $LANG['plugin_fusioninventory']["profile"][26];

	$sopt['PluginFusioninventoryAgent'][1]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][1]['field'] = 'name';
	$sopt['PluginFusioninventoryAgent'][1]['linkfield'] = 'name';
	$sopt['PluginFusioninventoryAgent'][1]['name'] = $LANG["common"][16];
   $sopt['PluginFusioninventoryAgent'][1]['datatype']='itemlink';
  
	$sopt['PluginFusioninventoryAgent'][30]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryAgent'][30]['linkfield'] = '';
	$sopt['PluginFusioninventoryAgent'][30]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryAgent'][4]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][4]['field'] = 'threads_discovery';
	$sopt['PluginFusioninventoryAgent'][4]['linkfield'] = 'threads_discovery';
	$sopt['PluginFusioninventoryAgent'][4]['name'] = $LANG['plugin_fusioninventory']["agents"][3];

	$sopt['PluginFusioninventoryAgent'][6]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][6]['field'] = 'threads_query';
	$sopt['PluginFusioninventoryAgent'][6]['linkfield'] = 'threads_query';
	$sopt['PluginFusioninventoryAgent'][6]['name'] = $LANG['plugin_fusioninventory']["agents"][2];

	$sopt['PluginFusioninventoryAgent'][8]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][8]['field'] = 'last_agent_update';
	$sopt['PluginFusioninventoryAgent'][8]['linkfield'] = '';
	$sopt['PluginFusioninventoryAgent'][8]['name'] = $LANG['plugin_fusioninventory']["agents"][4];
   $sopt['PluginFusioninventoryAgent'][8]['datatype']='datetime';
  
	$sopt['PluginFusioninventoryAgent'][9]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][9]['field'] = 'fusioninventory_agent_version';
	$sopt['PluginFusioninventoryAgent'][9]['linkfield'] = '';
	$sopt['PluginFusioninventoryAgent'][9]['name'] = $LANG['plugin_fusioninventory']["agents"][5];

	$sopt['PluginFusioninventoryAgent'][10]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][10]['field'] = 'lock';
	$sopt['PluginFusioninventoryAgent'][10]['linkfield'] = 'lock';
	$sopt['PluginFusioninventoryAgent'][10]['name'] = $LANG['plugin_fusioninventory']["agents"][6];
   $sopt['PluginFusioninventoryAgent'][10]['datatype']='bool';

 	$sopt['PluginFusioninventoryAgent'][11]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][11]['field'] = 'module_inventory';
	$sopt['PluginFusioninventoryAgent'][11]['linkfield'] = 'module_inventory';
	$sopt['PluginFusioninventoryAgent'][11]['name'] = $LANG['plugin_fusioninventory']['config'][3];
   $sopt['PluginFusioninventoryAgent'][11]['datatype']='bool';

 	$sopt['PluginFusioninventoryAgent'][12]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][12]['field'] = 'module_netdiscovery';
	$sopt['PluginFusioninventoryAgent'][12]['linkfield'] = 'module_netdiscovery';
	$sopt['PluginFusioninventoryAgent'][12]['name'] = $LANG['plugin_fusioninventory']['config'][4];
   $sopt['PluginFusioninventoryAgent'][12]['datatype']='bool';

   $sopt['PluginFusioninventoryAgent'][13]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][13]['field'] = 'module_snmpquery';
	$sopt['PluginFusioninventoryAgent'][13]['linkfield'] = 'module_snmpquery';
	$sopt['PluginFusioninventoryAgent'][13]['name'] = $LANG['plugin_fusioninventory']['config'][7];
   $sopt['PluginFusioninventoryAgent'][13]['datatype']='bool';

   $sopt['PluginFusioninventoryAgent'][14]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryAgent'][14]['field'] = 'module_wakeonlan';
	$sopt['PluginFusioninventoryAgent'][14]['linkfield'] = 'module_wakeonlan';
	$sopt['PluginFusioninventoryAgent'][14]['name'] = $LANG['plugin_fusioninventory']['config'][6];
   $sopt['PluginFusioninventoryAgent'][14]['datatype']='bool';

	$sopt['PluginFusioninventoryIPRange']['common'] = $LANG['plugin_fusioninventory']["profile"][25];

	$sopt['PluginFusioninventoryIPRange'][1]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][1]['field'] = 'name';
	$sopt['PluginFusioninventoryIPRange'][1]['linkfield'] = 'name';
	$sopt['PluginFusioninventoryIPRange'][1]['name'] = $LANG["common"][16];
   $sopt['PluginFusioninventoryIPRange'][1]['datatype']='itemlink';
  
	$sopt['PluginFusioninventoryIPRange'][2]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][2]['field'] = 'ifaddr_start';
	$sopt['PluginFusioninventoryIPRange'][2]['linkfield'] = 'ifaddr_start';
	$sopt['PluginFusioninventoryIPRange'][2]['name'] = $LANG['plugin_fusioninventory']["rangeip"][0];

	$sopt['PluginFusioninventoryIPRange'][3]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][3]['field'] = 'ifaddr_end';
	$sopt['PluginFusioninventoryIPRange'][3]['linkfield'] = 'ifaddr_end';
	$sopt['PluginFusioninventoryIPRange'][3]['name'] = $LANG['plugin_fusioninventory']["rangeip"][1];

	$sopt['PluginFusioninventoryIPRange'][30]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryIPRange'][30]['linkfield'] = '';
	$sopt['PluginFusioninventoryIPRange'][30]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryIPRange'][5]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryIPRange'][5]['field'] = 'name';
	$sopt['PluginFusioninventoryIPRange'][5]['linkfield'] = 'plugin_fusioninventory_agents_id_discover';
	$sopt['PluginFusioninventoryIPRange'][5]['name'] = $LANG['plugin_fusioninventory']["agents"][12];
	$sopt['PluginFusioninventoryIPRange'][5]['datatype']='itemlink';
	$sopt['PluginFusioninventoryIPRange'][5]['itemlink_type']='PluginFusioninventoryAgent';
   $sopt['PluginFusioninventoryIPRange'][5]['forcegroupby']='1';
  
	$sopt['PluginFusioninventoryIPRange'][6]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][6]['field'] = 'discover';
	$sopt['PluginFusioninventoryIPRange'][6]['linkfield'] = 'discover';
	$sopt['PluginFusioninventoryIPRange'][6]['name'] = $LANG['plugin_fusioninventory']["discovery"][3];
   $sopt['PluginFusioninventoryIPRange'][6]['datatype']='bool';
  
	$sopt['PluginFusioninventoryIPRange'][7]['table'] = 'glpi_plugin_fusioninventory_ipranges';
	$sopt['PluginFusioninventoryIPRange'][7]['field'] = 'query';
	$sopt['PluginFusioninventoryIPRange'][7]['linkfield'] = 'query';
	$sopt['PluginFusioninventoryIPRange'][7]['name'] = $LANG['plugin_fusioninventory']["rangeip"][3];
   $sopt['PluginFusioninventoryIPRange'][7]['datatype']='bool';
  
	$sopt['PluginFusioninventoryIPRange'][8]['table'] = 'glpi_entities';
	$sopt['PluginFusioninventoryIPRange'][8]['field'] = 'name';
	$sopt['PluginFusioninventoryIPRange'][8]['linkfield'] = 'entities_id';
	$sopt['PluginFusioninventoryIPRange'][8]['name'] = $LANG["entity"][0];

	$sopt['PluginFusioninventoryIPRange'][9]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryIPRange'][9]['field'] = 'name';
	$sopt['PluginFusioninventoryIPRange'][9]['linkfield'] = 'plugin_fusioninventory_agents_id_query';
	$sopt['PluginFusioninventoryIPRange'][9]['name'] = $LANG['plugin_fusioninventory']["agents"][13];
	$sopt['PluginFusioninventoryIPRange'][9]['datatype']='itemlink';
	$sopt['PluginFusioninventoryIPRange'][9]['itemlink_type']='PluginFusioninventoryAgent';
   $sopt['PluginFusioninventoryIPRange'][9]['forcegroupby']='1';

	$sopt['PluginFusioninventoryNetworkPortLog']['common'] = $LANG['plugin_fusioninventory']["title"][2];

	$sopt['PluginFusioninventoryNetworkPortLog'][1]['table'] = 'glpi_plugin_fusioninventory_networkportlogs';
	$sopt['PluginFusioninventoryNetworkPortLog'][1]['field'] = 'id';
	$sopt['PluginFusioninventoryNetworkPortLog'][1]['linkfield'] = '';
	$sopt['PluginFusioninventoryNetworkPortLog'][1]['name'] = $LANG["common"][2];

	$sopt['PluginFusioninventoryNetworkPortLog'][2]['table'] = 'glpi_networkports';
	$sopt['PluginFusioninventoryNetworkPortLog'][2]['field'] = 'id';
	$sopt['PluginFusioninventoryNetworkPortLog'][2]['linkfield'] = 'networkports_id';
	$sopt['PluginFusioninventoryNetworkPortLog'][2]['name'] = $LANG["setup"][175];

	$sopt['PluginFusioninventoryNetworkPortLog'][3]['table'] = 'glpi_plugin_fusioninventory_networkportlogs';
	$sopt['PluginFusioninventoryNetworkPortLog'][3]['field'] = 'field';
	$sopt['PluginFusioninventoryNetworkPortLog'][3]['linkfield'] = 'field';
	$sopt['PluginFusioninventoryNetworkPortLog'][3]['name'] = $LANG["event"][18];

	$sopt['PluginFusioninventoryNetworkPortLog'][4]['table'] = 'glpi_plugin_fusioninventory_networkportlogs';
	$sopt['PluginFusioninventoryNetworkPortLog'][4]['field'] = 'old_value';
	$sopt['PluginFusioninventoryNetworkPortLog'][4]['linkfield'] = 'old_value';
	$sopt['PluginFusioninventoryNetworkPortLog'][4]['name'] = $LANG['plugin_fusioninventory']["history"][0];

	$sopt['PluginFusioninventoryNetworkPortLog'][5]['table'] = 'glpi_plugin_fusioninventory_networkportlogs';
	$sopt['PluginFusioninventoryNetworkPortLog'][5]['field'] = 'new_value';
	$sopt['PluginFusioninventoryNetworkPortLog'][5]['linkfield'] = 'new_value';
	$sopt['PluginFusioninventoryNetworkPortLog'][5]['name'] = $LANG['plugin_fusioninventory']["history"][1];

	$sopt['PluginFusioninventoryNetworkPortLog'][6]['table'] = 'glpi_plugin_fusioninventory_networkportlogs';
	$sopt['PluginFusioninventoryNetworkPortLog'][6]['field'] = 'date_mod';
	$sopt['PluginFusioninventoryNetworkPortLog'][6]['linkfield'] = 'date_mod';
	$sopt['PluginFusioninventoryNetworkPortLog'][6]['name'] = $LANG["common"][27];
	$sopt['PluginFusioninventoryNetworkPortLog'][6]['datatype']='datetime';


	$sopt['PluginFusioninventoryNetworkport2']['common'] = $LANG['plugin_fusioninventory']["profile"][28];

	$sopt['PluginFusioninventoryNetworkport2'][30]['table'] = 'glpi_plugin_fusioninventory_networkports';
	$sopt['PluginFusioninventoryNetworkport2'][30]['field'] = 'id';
	$sopt['PluginFusioninventoryNetworkport2'][30]['linkfield'] = '';
	$sopt['PluginFusioninventoryNetworkport2'][30]['name'] = $LANG["reports"][52];

	$sopt['PluginFusioninventoryNetworkport2'][1]['table'] = 'glpi_plugin_fusioninventory_networkports';
	$sopt['PluginFusioninventoryNetworkport2'][1]['field'] = 'networkports_id';
	$sopt['PluginFusioninventoryNetworkport2'][1]['linkfield'] = 'networkports_id';
	$sopt['PluginFusioninventoryNetworkport2'][1]['name'] = $LANG["setup"][175];

	$sopt['PluginFusioninventoryNetworkport2'][2]['table'] = 'glpi_locations';
	$sopt['PluginFusioninventoryNetworkport2'][2]['field'] = 'id';
	$sopt['PluginFusioninventoryNetworkport2'][2]['linkfield'] = 'networkports_id';
	$sopt['PluginFusioninventoryNetworkport2'][2]['name'] = $LANG["common"][15];

	$sopt['PluginFusioninventoryNetworkport2'][3]['table'] = 'glpi_plugin_fusioninventory_networkports';
	$sopt['PluginFusioninventoryNetworkport2'][3]['field'] = 'lastup';
	$sopt['PluginFusioninventoryNetworkport2'][3]['linkfield'] = 'lastup';
	$sopt['PluginFusioninventoryNetworkport2'][3]['name'] = $LANG["login"][0];


	$sopt[NETWORKING_TYPE][5190]['table']='glpi_plugin_fusioninventory_snmpmodels';
	$sopt[NETWORKING_TYPE][5190]['field']='id';
	$sopt[NETWORKING_TYPE][5190]['linkfield']='id';
	$sopt[NETWORKING_TYPE][5190]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[NETWORKING_TYPE][5191]['table'] = 'glpi_plugin_fusioninventory_networkequipments';
		$sopt[NETWORKING_TYPE][5191]['field'] = 'plugin_fusioninventory_snmpauths_id';
		$sopt[NETWORKING_TYPE][5191]['linkfield'] = 'id';
		$sopt[NETWORKING_TYPE][5191]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	} else {
		$sopt[NETWORKING_TYPE][5191]['table']='glpi_plugin_fusioninventory_configsnmpsecurities';
		$sopt[NETWORKING_TYPE][5191]['field']='name';
		$sopt[NETWORKING_TYPE][5191]['linkfield']='id';
		$sopt[NETWORKING_TYPE][5191]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	}

	$sopt[NETWORKING_TYPE][5194]['table']='glpi_plugin_fusioninventory_networkequipments';
	$sopt[NETWORKING_TYPE][5194]['field']='networkequipments_id';
	$sopt[NETWORKING_TYPE][5194]['linkfield']='id';
	$sopt[NETWORKING_TYPE][5194]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][53];

	$sopt[NETWORKING_TYPE][5195]['table']='glpi_plugin_fusioninventory_networkequipments';
	$sopt[NETWORKING_TYPE][5195]['field']='cpu';
	$sopt[NETWORKING_TYPE][5195]['linkfield']='id';
	$sopt[NETWORKING_TYPE][5195]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][13];


	$sopt[PRINTER_TYPE][5190]['table']='glpi_plugin_fusioninventory_snmpmodels';
	$sopt[PRINTER_TYPE][5190]['field']='id';
	$sopt[PRINTER_TYPE][5190]['linkfield']='id';
	$sopt[PRINTER_TYPE][5190]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][19];

	if ($config->getValue("authsnmp") == "file") {
		$sopt[PRINTER_TYPE][5191]['table'] = 'glpi_plugin_fusioninventory_printers';
		$sopt[PRINTER_TYPE][5191]['field'] = 'plugin_fusioninventory_snmpauths_id';
		$sopt[PRINTER_TYPE][5191]['linkfield'] = 'id';
		$sopt[PRINTER_TYPE][5191]['name'] = $LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	} else {
		$sopt[PRINTER_TYPE][5191]['table']='glpi_plugin_fusioninventory_configsnmpsecurities';
		$sopt[PRINTER_TYPE][5191]['field']='id';
		$sopt[PRINTER_TYPE][5191]['linkfield']='id';
		$sopt[PRINTER_TYPE][5191]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["profile"][20];
	}

	$sopt[PRINTER_TYPE][5194]['table']='glpi_plugin_fusioninventory_printers';
	$sopt[PRINTER_TYPE][5194]['field']='printers_id';
	$sopt[PRINTER_TYPE][5194]['linkfield']='id';
	$sopt[PRINTER_TYPE][5194]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG['plugin_fusioninventory']["snmp"][53];

	$sopt[PRINTER_TYPE][5196]['table']='glpi_plugin_fusioninventory_networkequipments';
	$sopt[PRINTER_TYPE][5196]['field']='id';
	$sopt[PRINTER_TYPE][5196]['linkfield']='id';
	$sopt[PRINTER_TYPE][5196]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
	$sopt[PRINTER_TYPE][5196]['forcegroupby']='1';

	$sopt[PRINTER_TYPE][5197]['table']='glpi_plugin_fusioninventory_networkports';
	$sopt[PRINTER_TYPE][5197]['field']='id';
	$sopt[PRINTER_TYPE][5197]['linkfield']='id';
	$sopt[PRINTER_TYPE][5197]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
	$sopt[PRINTER_TYPE][5197]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5192]['table']='glpi_plugin_fusioninventory_networkequipments';
	$sopt[COMPUTER_TYPE][5192]['field']='id';
	$sopt[COMPUTER_TYPE][5192]['linkfield']='id';
	$sopt[COMPUTER_TYPE][5192]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][52];
	$sopt[COMPUTER_TYPE][5192]['forcegroupby']='1';

	$sopt[COMPUTER_TYPE][5193]['table']='glpi_plugin_fusioninventory_networkports';
	$sopt[COMPUTER_TYPE][5193]['field']='id';
	$sopt[COMPUTER_TYPE][5193]['linkfield']='id';
	$sopt[COMPUTER_TYPE][5193]['name']=$LANG['plugin_fusioninventory']["title"][0]." - ".$LANG["reports"][46];
	$sopt[COMPUTER_TYPE][5193]['forcegroupby']='1';



	$sopt['PluginFusioninventoryTask']['common'] = $LANG['plugin_fusioninventory']["task"][0];

	$sopt['PluginFusioninventoryTask'][1]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][1]['field'] = 'id';
	$sopt['PluginFusioninventoryTask'][1]['linkfield'] = 'id';
	$sopt['PluginFusioninventoryTask'][1]['name'] = $LANG["common"][16];
   $sopt['PluginFusioninventoryTask'][1]['datatype']='itemlink';

	$sopt['PluginFusioninventoryTask'][2]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][2]['field'] = 'date';
	$sopt['PluginFusioninventoryTask'][2]['linkfield'] = 'date';
	$sopt['PluginFusioninventoryTask'][2]['name'] = $LANG["common"][27];
   $sopt['PluginFusioninventoryTask'][2]['datatype']='datetime';

 	$sopt['PluginFusioninventoryTask'][3]['table'] = 'glpi_plugin_fusioninventory_agents';
	$sopt['PluginFusioninventoryTask'][3]['field'] = 'name';
	$sopt['PluginFusioninventoryTask'][3]['linkfield'] = 'agent_id';
	$sopt['PluginFusioninventoryTask'][3]['name'] = $LANG['plugin_fusioninventory']["agents"][13];
	$sopt['PluginFusioninventoryTask'][3]['datatype']='itemlink';
	$sopt['PluginFusioninventoryTask'][3]['itemlink_type']='PluginFusioninventoryAgent';
   $sopt['PluginFusioninventoryTask'][3]['forcegroupby']='1';

	$sopt['PluginFusioninventoryTask'][4]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][4]['field'] = 'action';
	$sopt['PluginFusioninventoryTask'][4]['linkfield'] = 'action';
	$sopt['PluginFusioninventoryTask'][4]['name'] = $LANG['plugin_fusioninventory']["task"][0];

	$sopt['PluginFusioninventoryTask'][5]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][5]['field'] = 'param';
	$sopt['PluginFusioninventoryTask'][5]['linkfield'] = 'param';
	$sopt['PluginFusioninventoryTask'][5]['name'] = $LANG['plugin_fusioninventory']["task"][2];
   
	$sopt['PluginFusioninventoryTask'][6]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][6]['field'] = 'itemtype';
	$sopt['PluginFusioninventoryTask'][6]['linkfield'] = 'itemtype';
	$sopt['PluginFusioninventoryTask'][6]['name'] = $LANG["common"][1];
 
//	$sopt['PluginFusioninventoryTask'][7]['table'] = 'glpi_plugin_fusioninventory_tasks';
//	$sopt['PluginFusioninventoryTask'][7]['field'] = 'items_id';
//	$sopt['PluginFusioninventoryTask'][7]['linkfield'] = 'items_id';
//	$sopt['PluginFusioninventoryTask'][7]['name'] = $LANG["common"][27];

	$sopt['PluginFusioninventoryTask'][8]['table'] = 'glpi_plugin_fusioninventory_tasks';
	$sopt['PluginFusioninventoryTask'][8]['field'] = 'single';
	$sopt['PluginFusioninventoryTask'][8]['linkfield'] = 'single';
	$sopt['PluginFusioninventoryTask'][8]['name'] = $LANG['plugin_fusioninventory']["task"][3];



	$sopt['PluginFusioninventoryConstructDevices']['common'] = $LANG['plugin_fusioninventory']["constructdevice"][0];

	$sopt['PluginFusioninventoryConstructDevices'][1]['table'] = 'glpi_plugin_fusioninventory_constructdevices';
	$sopt['PluginFusioninventoryConstructDevices'][1]['field'] = 'id';
	$sopt['PluginFusioninventoryConstructDevices'][1]['linkfield'] = 'id';
	$sopt['PluginFusioninventoryConstructDevices'][1]['name'] = $LANG["common"][16];
   $sopt['PluginFusioninventoryConstructDevices'][1]['datatype']='itemlink';

  	$sopt['PluginFusioninventoryConstructDevices'][2]['table'] = 'glpi_manufacturers';
	$sopt['PluginFusioninventoryConstructDevices'][2]['field'] = 'name';
	$sopt['PluginFusioninventoryConstructDevices'][2]['linkfield'] = 'manufacturer';
	$sopt['PluginFusioninventoryConstructDevices'][2]['name'] = $LANG['common'][5];

	$sopt['PluginFusioninventoryConstructDevices'][3]['table'] = 'glpi_plugin_fusioninventory_constructdevices';
	$sopt['PluginFusioninventoryConstructDevices'][3]['field'] = 'device';
	$sopt['PluginFusioninventoryConstructDevices'][3]['linkfield'] = 'device';
	$sopt['PluginFusioninventoryConstructDevices'][3]['name'] = $LANG['common'][1];
   $sopt['PluginFusioninventoryConstructDevices'][3]['datatype']='text';

	$sopt['PluginFusioninventoryConstructDevices'][4]['table'] = 'glpi_plugin_fusioninventory_constructdevices';
	$sopt['PluginFusioninventoryConstructDevices'][4]['field'] = 'firmware';
	$sopt['PluginFusioninventoryConstructDevices'][4]['linkfield'] = 'firmware';
	$sopt['PluginFusioninventoryConstructDevices'][4]['name'] = $LANG['setup'][71];
   $sopt['PluginFusioninventoryConstructDevices'][4]['datatype']='text';

	$sopt['PluginFusioninventoryConstructDevices'][5]['table'] = 'glpi_plugin_fusioninventory_constructdevices';
	$sopt['PluginFusioninventoryConstructDevices'][5]['field'] = 'sysdescr';
	$sopt['PluginFusioninventoryConstructDevices'][5]['linkfield'] = 'sysdescr';
	$sopt['PluginFusioninventoryConstructDevices'][5]['name'] = $LANG['common'][25];
   $sopt['PluginFusioninventoryConstructDevices'][5]['datatype']='text';

	$sopt['PluginFusioninventoryConstructDevices'][6]['table'] = 'glpi_plugin_fusioninventory_constructdevices';
	$sopt['PluginFusioninventoryConstructDevices'][6]['field'] = 'type';
	$sopt['PluginFusioninventoryConstructDevices'][6]['linkfield'] = 'type';
	$sopt['PluginFusioninventoryConstructDevices'][6]['name'] = $LANG['common'][17];
   $sopt['PluginFusioninventoryConstructDevices'][6]['datatype']='number';

	$sopt['PluginFusioninventoryConstructDevices'][7]['table'] = 'glpi_plugin_fusioninventory_snmpmodels';
	$sopt['PluginFusioninventoryConstructDevices'][7]['field'] = 'name';
	$sopt['PluginFusioninventoryConstructDevices'][7]['linkfield'] = 'snmpmodel_id';
	$sopt['PluginFusioninventoryConstructDevices'][7]['name'] = $LANG['plugin_fusioninventory']["profile"][24];
   $sopt['PluginFusioninventoryConstructDevices'][7]['datatype']='itemptype';

   

	return $sopt;
}


function plugin_fusioninventory_giveItem($type,$id,$data,$num) {
	global $CFG_GLPI, $DB, $INFOFORM_PAGES, $LINK_ID_TABLE,$LANG,$SEARCH_OPTION,$FUSIONINVENTORY_MAPPING;

	$table=$SEARCH_OPTION[$type][$id]["table"];
	$field=$SEARCH_OPTION[$type][$id]["field"];

//	echo "GiveItem : ".$field."<br/>";
	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networkequipments.id" :
					$out = '';
					$NetworkPort = new NetworkPort;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $NetworkPort->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?id=".$vartmp."\">";
                  $out .=  $NetworkPort->device_name;
                  $out .= $vartmp;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networkports.id" :
					$out = '';
					if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->fields["name"]."</a><br/>";
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
				case "glpi_plugin_fusioninventory_networkequipments.networkequipments_id" :
					$query = "SELECT *
                         FROM `glpi_plugin_fusioninventory_networkequipments`
                         WHERE `networkequipments_id` = '".$data["id"]."';";
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
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySNMP;
					$FK_model_DB = $plugin_fusioninventory_snmp->GetSNMPModel($data["id"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/models.form.php?id=" . $FK_model_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodels", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.name" :
					$plugin_fusioninventory_snmp = new PluginFusioninventoryConfigSNMPSecurity;
					$FK_auth_DB = $plugin_fusioninventory_snmp->GetSNMPAuth($data["id"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/snmp_auth.form.php?id=" . $FK_auth_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_configsnmpsecurities", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;
			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               $out = '';
               $NetworkPort = new NetworkPort;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $NetworkPort->getDeviceData($vartmp,NETWORKING_TYPE);

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[NETWORKING_TYPE]."?id=".$vartmp."\">";
                  $out .=  $NetworkPort->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               $out = '';
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.printers_id" :
					$query = "SELECT *
                         FROM `glpi_plugin_fusioninventory_printers`
                         WHERE `printers_id` = '".$data["id"]."';";
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
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					$plugin_fusioninventory_snmp = new PluginFusioninventorySNMP;
					$FK_model_DB = $plugin_fusioninventory_snmp->GetSNMPModel($data["id"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/models.form.php?id=" . $FK_model_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_snmpmodels", $FK_model_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.id" :
					$plugin_fusioninventory_snmp = new PluginFusioninventoryConfigSNMPSecurity;
					$FK_auth_DB = $plugin_fusioninventory_snmp->GetSNMPAuth($data["id"],$type);
					$out = "<a href=\"" . $CFG_GLPI["root_doc"] . "/plugins/fusioninventory/front/snmp_auth.form.php?id=" . $FK_auth_DB . "\">";
					$out .= Dropdown::getDropdownName("glpi_plugin_fusioninventory_configsnmpsecurities", $FK_auth_DB, 0);
					$out .= "</a>";
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * Model List (plugins/fusioninventory/front/models.php)
		case 'PluginFusioninventoryModelinfo' :
			switch ($table.'.'.$field) {

				// ** Name of type of model (network, printer...)
				case "glpi_plugin_fusioninventory_snmpmodels.itemtype" :
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
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					$out = "<div align='center'><form></form><form method='get' action='" . GLPI_ROOT . "/plugins/fusioninventory/front/models.export.php' target='_blank'>
						<input type='hidden' name='model' value='" . $data["id"] . "' />
						<input name='export' src='" . GLPI_ROOT . "/pics/right.png' title='Exporter' value='Exporter' type='image'>
						</form></div>";
					return "<center>".$out."</center>";
					break;

			}
			break;


		// * Authentification List (plugins/fusioninventory/front/snmp_auth.php)
		case 'PluginFusioninventoryConfigSNMPSecurity' :
			switch ($table.'.'.$field) {

				// ** Hidden auth passphrase (SNMP v3)
				case "glpi_plugin_fusioninventory_configsnmpsecurities.auth_passphrase" :
               $out = "";
					if (empty($data["ITEM_$num"])) {
						
               } else {
						$out = "********";
               }
					return $out;
					break;

				// ** Hidden priv passphrase (SNMP v3)
				case "glpi_plugin_fusioninventory_configsnmpsecurities.priv_passphrase" :
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
		case 'PluginFusioninventoryUnknownDevice' :
			switch ($table.'.'.$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               $out = '';
               $NetworkPort = new NetworkPort;
               $list = explode("$$$$",$data["ITEM_$num"]);
               foreach ($list as $numtmp=>$vartmp) {
                  $NetworkPort->getDeviceData($vartmp,'PluginFusioninventoryUnknownDevice');

                  $out .= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES['PluginFusioninventoryUnknownDevice']."?id=".$vartmp."\">";
                  $out .=  $NetworkPort->device_name;
                  if ($CFG_GLPI["view_ID"]) $out .= " (".$vartmp.")";
                  $out .=  "</a><br/>";
               }
               return "<center>".$out."</center>";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               $out = '';
               if (!empty($data["ITEM_$num"])) {
                  $list = explode("$$$$",$data["ITEM_$num"]);
                  $np = new NetworkPort;
                  foreach ($list as $numtmp=>$vartmp) {
                     $np->getFromDB($vartmp);
                     $out .= "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$vartmp."'>".$np->fields["name"]."</a><br/>";
                  }
               }
               return "<center>".$out."</center>";
               break;

            case "glpi_plugin_fusioninventory_unknowndevices.type" :
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
		case 'PluginFusioninventoryNetworkPort' :
			switch ($table.'.'.$field) {

			}
			break;

		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case 'PluginFusioninventoryNetworkport2' :
			switch ($table.'.'.$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_fusioninventory_networkports.id" :
					$query = "SELECT `glpi_networkequipments`.`name` AS `name`, `glpi_networkequipments`.`id` AS `id`
                         FROM `glpi_networkequipments`
                              LEFT JOIN `glpi_networkports`
                                        ON `items_id` = `glpi_networkequipments`.`id`
                              LEFT JOIN `glpi_plugin_fusioninventory_networkports`
                                        ON `glpi_networkports`.`id`=`networkports_id`
                         WHERE `glpi_plugin_fusioninventory_networkports`.`id`='".$data["ITEM_$num"]."'
                         LIMIT 0,1;";
					$result = $DB->query($query);
					$data2 = $DB->fetch_assoc($result);
					$out = "<a href='".GLPI_ROOT."/front/networking.form.php?id=".$data2["id"]."'>".$data2["name"]."</a>";
				return "<center>".$out."</center>";
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_fusioninventory_networkports.networkports_id" :
					$NetworkPort=new NetworkPort;
					$NetworkPort->getFromDB($data["ITEM_$num"]);
               $name = "";
               if (isset($NetworkPort->fields["name"])) {
                  $name = $NetworkPort->fields["name"];
               }
					$out = "<a href='".GLPI_ROOT."/front/networkport.form.php?id=".$data["ITEM_$num"]."'>".$name."</a>";
					return "<center>".$out."</center>";
					break;

				// ** Location of switch
				case "glpi_locations.id" :
					$out = Dropdown::getDropdownName("glpi_locations",$data["ITEM_$num"]);
					return "<center>".$out."</center>";
					break;

			}
			break;

		// * FusionInventory Agents list (plugins/fusioninventory/front/agent.php)
		case 'PluginFusioninventoryAgent' :
			break;

		// * range IP list (plugins/fusioninventory/front/iprange.php)
		case 'PluginFusioninventoryIPRange' :
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
		case 'PluginFusioninventoryNetworkPortLog' :
			switch ($table.'.'.$field) {

				// ** Display switch and Port
				case "glpi_networkports.id" :
					$Array_device = PluginFusioninventoryNetworkPort::getUniqueObjectfieldsByportID($data["ITEM_$num"]);
					$item = new $Array_device["itemtype"];
					$item->getFromDB($Array_device["items_id"]);
					$out = "<div align='center'>" . $item->getLink(1);

					$query = "SELECT *
                         FROM `glpi_networkports`
                         WHERE `id`='" . $data["ITEM_$num"] . "';";
					$result = $DB->query($query);

					if ($DB->numrows($result) != "0") {
						$out .= "<br/><a href='".GLPI_ROOT."/front/networkport.form.php?id=".$data["ITEM_$num"]."'>".$DB->result($result, 0, "name")."</a>";
               }
					$out .= "</td>";
					return $out;
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_networkportlogs.field" :
               $out = $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$data["ITEM_$num"]]['name'];
               return $out;
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_fusioninventory_networkportlogs.old_value" :
					// TODO ADD LINK TO DEVICE
					if ((substr_count($data["ITEM_$num"],":") == 5) AND (empty($data["ITEM_3"]))) {
						return "<center><b>".$data["ITEM_$num"]."</b></center>";
               }
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_fusioninventory_networkportlogs.new_value" :
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
			"glpi_plugin_fusioninventory_miboids" => "OID MIB",
			"glpi_plugin_fusioninventory_mibobjects" => "Objet MIB",
			"glpi_plugin_fusioninventory_miblabels" => "Label MIB"
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
   $pfisnmph = new PluginFusioninventoryNetworkPortLog;
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
         PluginFusioninventorySetup::install("2.3.0");
   } else if ((TableExists("glpi_plugin_tracker_config")) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {
      //$config = new PluginFusioninventoryConfig;
      if ((!TableExists("glpi_plugin_tracker_agents")) &&
         (!TableExists("glpi_plugin_fusioninventory_agents"))) {
         PluginFusioninventorySetup::update("1.1.0");
      }
      if ((!TableExists("glpi_plugin_tracker_config_discovery")) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         PluginFusioninventorySetup::update("2.0.0");
      }
      if ((TableExists("glpi_plugin_tracker_agents")) &&
         (!FieldExists("glpi_plugin_tracker_config", "version")) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         PluginFusioninventorySetup::update("2.0.2");
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (FieldExists("glpi_plugin_tracker_config", "version"))) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {
         if (TableExists("glpi_plugin_tracker_agents")) {
            $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";
         } else if (TableExists("glpi_plugin_fusioninventory_config")) {
            $query = "SELECT version FROM glpi_plugin_fusioninventory_config LIMIT 1";
         }
         if ($result=$DB->query($query)) {
            if ($DB->numrows($result) == "1") {
               $data = $DB->fetch_assoc($result);
            }
         }
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
         if ($data['version'] == "2.2.0") {
            PluginFusioninventorySetup::update("2.2.1");
            $DB->query("UPDATE `glpi_plugin_fusioninventory_config`
                        SET `version` = '2.2.1'
                        WHERE `ID`='1';");
         }
         if ($data['version'] == "2.2.1") {
            PluginFusioninventorySetup::update("2.3.0");
            $DB->query("UPDATE `glpi_plugin_fusioninventory_configs`
                        SET `version` = '2.3.0'
                        WHERE `id`='1';");
         }
      }
   } else if (TableExists("glpi_plugin_fusioninventory_configs")) {

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
	if (!TableExists("glpi_plugin_fusioninventory_configs")) {
		return 0; // Installation
   } else if (!TableExists("glpi_plugin_fusioninventory_agents")) {
		return 1; //Update
   } else if (!TableExists("glpi_plugin_fusioninventory_config_discovery")) {
//		return 1; // Update (Bug with new version SVN 2.1.4
   } else if (!FieldExists("glpi_plugin_fusioninventory_configs", "version")) {
      return 1; // Update
   } else if (FieldExists("glpi_plugin_fusioninventory_configs", "version")) {
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
//function plugin_get_headings_fusioninventory($type,$id,$withtemplate) {
function plugin_get_headings_fusioninventory($item,$withtemplate) {
	global $LANG;
	$configModules = new PluginFusioninventoryConfigModules;

	$type = get_Class($item);
   switch ($type) {
		case COMPUTER_TYPE :
			if ($withtemplate) { //?
				return array();
			// Non template case
         } else {
//				if ((PluginFusioninventoryAuth::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
				$array = array ();
            //return array(
            if (($configModules->isActivated('remotehttpagent')) AND(PluginFusioninventoryAuth::haveRight("remotecontrol","w"))) {
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
//				if ((PluginFusioninventoryAuth::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
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
				if ((PluginFusioninventoryAuth::haveRight("snmp_networking", "r")) AND ($configModules->getValue("snmp") == "1")) {
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
				if ((PluginFusioninventoryAuth::haveRight("snmp_printers", "r")) AND ($configModules->getValue("snmp") == "1")) {
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
         if (($configModules->isActivated('remotehttpagent')) AND (PluginFusioninventoryAuth::haveRight("remotecontrol","w"))) {
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
			if (PluginFusioninventoryAuth::haveRight("snmp_printers", "r")) {
				$array[1] = "plugin_headings_fusioninventory_printerInfo";
			}
         $array[2] = "plugin_headings_fusioninventory_fusioninventoryLocks";
			return $array;
			break;

		case NETWORKING_TYPE :
			if (PluginFusioninventoryAuth::haveRight("snmp_networking", "r")) {
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


function plugin_headings_fusioninventory_computerInfo($type, $id) {
   $pfit = new PluginFusioninventoryTask;
   $pfit->RemoteStateAgent(GLPI_ROOT . '/plugins/fusioninventory/front/agents.state.php', $id, $type, array('INVENTORY' => 1, 'NETDISCOVERY' => 1, 'SNMPQUERY' => 1, 'WAKEONLAN' => 1));
}

function plugin_headings_fusioninventory_printerInfo($type, $id) {
	include_once(GLPI_ROOT."/inc/stat.function.php");
	$plugin_fusioninventory_printer = new PluginFusioninventoryPrinter;
	$plugin_fusioninventory_printer->showFormPrinter($id, 
               array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/printer_info.form.php'));
	echo '<div id="overDivYFix" STYLE="visibility:hidden">fusioninventory_1</div>';
   $plugin_fusioninventory_printer->showFormPrinter_graph($id, 
               array('target'=>GLPI_ROOT . '/plugins/fusioninventory/front/printer_info.form.php'));
}

function plugin_headings_fusioninventory_printerHistory($type, $id) {
	$print_history = new PluginFusioninventoryPrinterLog;
	$print_history->showForm($_GET["id"],
               array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/printer_history.form.php'));
}

function plugin_headings_fusioninventory_networkingInfo($type, $id) {
	$snmp = new PluginFusioninventoryNetworkEquipment;
	$snmp->showForm($id, 
           array('target'=>GLPI_ROOT.'/plugins/fusioninventory/front/switch_info.form.php'));
}

function plugin_headings_fusioninventory_fusioninventoryLocks($type, $id) {
	$fusioninventory_locks = new PluginFusioninventoryLock();
	$fusioninventory_locks->showForm(GLPI_ROOT . '/plugins/fusioninventory/front/lock.form.php', $type, $id);
}

function plugin_headings_fusioninventory($type,$id,$withtemplate=0) {
	global $CFG_GLPI;

	switch ($type) {
		case PROFILE_TYPE :
			$prof=new PluginFusioninventoryProfile;
			if (!$prof->GetfromDB($id)) {
				PluginFusioninventory::createaccess($id);
         }
			$prof->showForm($id, 
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

		case 'PluginFusioninventoryUnknownDevice';
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
               if(PluginFusioninventoryAuth::haveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_model" :
               if(PluginFusioninventoryAuth::haveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusioninventory_snmpmodels`
                                   WHERE `itemtype`!='2'
                                         AND `itemtype`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['id'];
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
               if(PluginFusioninventoryAuth::haveRight("snmp_authentication","w")) {
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
               if(PluginFusioninventoryAuth::haveRight("snmp_models","w")) {
                   echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . $LANG["buttons"][2] . "\" >";
               }
               break;

				case "plugin_fusioninventory_assign_model" :
               if(PluginFusioninventoryAuth::haveRight("snmp_models","w")) {
                  $query_models = "SELECT *
                                   FROM `glpi_plugin_fusioninventory_snmpmodels`
                                   WHERE `itemtype`!='3'
                                         AND `itemtype`!='0';";
                  $result_models=$DB->query($query_models);
                  $exclude_models = array();
                  while ($data_models=$DB->fetch_array($result_models)) {
                     $exclude_models[] = $data_models['id'];
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
               if(PluginFusioninventoryAuth::haveRight("snmp_authentication","w")) {
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

		case 'PluginFusioninventoryUnknownDevice';
			switch ($action) {
				case "plugin_fusioninventory_discovery_import" :
               if(PluginFusioninventoryAuth::haveRight("unknowndevices","w")) {
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
         if(PluginFusioninventoryAuth::haveRight("unknowndevices","w")) {
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

		case 'glpi_plugin_fusioninventory_configsnmpsecurities.name':
			Dropdown::show("PluginFusioninventoryConfigSNMPSecurity",
                        array('name' => $linkfield));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_snmpmodels.name':
			Dropdown::show("PluginFusioninventoryModelInfos",
                        array('name' => $linkfield,
                              'comment' => false));
			return true;
			break;

		case 'glpi_plugin_fusioninventory_unknowndevices.type' :
         $type_list = array();
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;
			Device::dropdownTypes('type',$linkfield,$type_list);
			return true;
			break;

		case 'glpi_plugin_fusioninventory_agents.id' :
			Dropdown::show("PluginFusioninventoryAgent",
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
			$plugin_fusioninventory_snmp = new PluginFusioninventoryConfigSNMPSecurity;
			echo $plugin_fusioninventory_snmp->selectbox();
			return true;
			break;

		case 'glpi_plugin_fusioninventory_snmpmodels.itemtype' :
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



function plugin_fusioninventory_addSelect($type,$id,$num) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$id]["table"];
	$field=$SEARCH_OPTION[$type][$id]["field"];

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

			// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networkequipments.id" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networkports.id" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;
			}
			break;
		// * PRINTER List (front/printer.php)
      case PRINTER_TYPE :
         switch ($table.".".$field) {

         // ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

				// ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
               break;

         }
         break;

		case 'PluginFusioninventoryUnknownDevice' :
			switch ($table.".".$field) {

				case "glpi_networkequipments.device" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_12.items_id SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

				case "glpi_networkports.NetworkPort" :
					return "GROUP_CONCAT( DISTINCT FUSIONINVENTORY_22.".$field." SEPARATOR '$$$$') AS ITEM_$num, ";
					break;

			}
			break;

      case 'PluginFusioninventoryIPRange' :
         switch ($table.".".$SEARCH_OPTION[$type][$id]["linkfield"]) {

            case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_query" :
               return "GROUP_CONCAT( DISTINCT CONCAT(gpta.name,'$$' ,gpta.id) SEPARATOR '$$$$') AS ITEM_$num, ";
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
				case "glpi_plugin_fusioninventory_networkequipments.id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.end1 OR glpi_networkports.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networkports.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (FUSIONINVENTORY_10.items_id = glpi_computers.id AND FUSIONINVENTORY_10.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";
               }
               break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networkports.id" :
               $table_networking_ports = 0;
               $table_fusioninventory_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusioninventory_networkequipments.id") {
                     $table_fusioninventory_networking = 1;
                  }
               }
               if ($table_fusioninventory_networking == "1") {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id=FUSIONINVENTORY_12.id ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON glpi_networkports.id = FUSIONINVENTORY_21.end1 OR glpi_networkports.id = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networkports.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_computers.id AND FUSIONINVENTORY_20.itemtype='".COMPUTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";

               }
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networkequipments.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networkequipments ON (glpi_networkequipments.id = glpi_plugin_fusioninventory_networkequipments.networkequipments_id) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networkequipments AS gptn_model ON (glpi_networkequipments.id = gptn_model.networkequipments_id) ".
						" LEFT JOIN glpi_plugin_fusioninventory_snmpmodels ON (gptn_model.plugin_fusioninventory_snmpmodels_id = glpi_plugin_fusioninventory_snmpmodels.id) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_networkequipments AS gptn_auth ON glpi_networkequipments.id = gptn_auth.networkequipments_id ".
						" LEFT JOIN glpi_plugin_fusioninventory_configsnmpsecurities ON gptn_auth.plugin_fusioninventory_snmpauths_id = glpi_plugin_fusioninventory_configsnmpsecurities.id ";
					break;

			}
			break;

		// * Printer List (front/printer.php)
		case PRINTER_TYPE :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_printers.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers ON (glpi_printers.id = glpi_plugin_fusioninventory_printers.printers_id) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers AS gptp_model ON (glpi_printers.id = gptp_model.printers_id) ".
						" LEFT JOIN glpi_plugin_fusioninventory_snmpmodels ON (gptp_model.plugin_fusioninventory_snmpmodels_id = glpi_plugin_fusioninventory_snmpmodels.id) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.id" :
					return " LEFT JOIN glpi_plugin_fusioninventory_printers AS gptp_auth ON glpi_printers.id = gptp_auth.printers_id ".
						" LEFT JOIN glpi_plugin_fusioninventory_configsnmpsecurities ON gptp_auth.plugin_fusioninventory_snmpauths_id = glpi_plugin_fusioninventory_configsnmpsecurities.id ";
					break;

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networkequipments.id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.end1 OR glpi_networkports.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networkports.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (glpi_printers.id = FUSIONINVENTORY_10.items_id AND FUSIONINVENTORY_10.itemtype='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";
               }
               break;

               // ** FusionInventory - switch port
               case "glpi_plugin_fusioninventory_networkports.id" :
                  $table_networking_ports = 0;
                  $table_fusioninventory_networking = 0;
                  foreach ($already_link_tables AS $num=>$tmp_table) {
                     if ($tmp_table == "glpi_networkports.") {
                        $table_networking_ports = 1;
                     }
                     if ($tmp_table == "glpi_plugin_fusioninventory_networkequipments.id") {
                        $table_fusioninventory_networking = 1;
                     }
                  }
                  if ($table_fusioninventory_networking == "1") {
                     return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id=FUSIONINVENTORY_12.id ";
                  } else if ($table_networking_ports == "1") {
                     return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON glpi_networkports.id = FUSIONINVENTORY_21.end1 OR glpi_networkports.id = FUSIONINVENTORY_21.end2 ".
                        " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networkports.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
                  } else {
                     return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_computers.id AND FUSIONINVENTORY_20.itemtype='".PRINTER_TYPE."') ".
                      " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end2 ".
                        " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
                  }
                  break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case 'PluginFusioninventoryUnknownDevice' :
			switch ($new_table.".".$linkfield) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networkequipments.id" :
               $table_networking_ports = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
               }
               if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON glpi_networkports.id = FUSIONINVENTORY_11.end1 OR glpi_networkports.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = glpi_networkports.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";

               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_10 ON (glpi_printers.id = FUSIONINVENTORY_10.items_id AND FUSIONINVENTORY_10.itemtype='".PRINTER_TYPE."') ".
                     " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_11 ON FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end1 OR FUSIONINVENTORY_10.id = FUSIONINVENTORY_11.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_12 ON FUSIONINVENTORY_12.id = CASE WHEN FUSIONINVENTORY_11.end1 = FUSIONINVENTORY_10.id THEN FUSIONINVENTORY_11.end2 ELSE FUSIONINVENTORY_11.end1 END
                     LEFT JOIN glpi_networkequipments AS FUSIONINVENTORY_13 ON FUSIONINVENTORY_12.items_id=FUSIONINVENTORY_13.id";
               }
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               $table_networking_ports = 0;
               $table_fusioninventory_networking = 0;
               foreach ($already_link_tables AS $num=>$tmp_table) {
                  if ($tmp_table == "glpi_networkports.") {
                     $table_networking_ports = 1;
                  }
                  if ($tmp_table == "glpi_plugin_fusioninventory_networkequipments.id") {
                     $table_fusioninventory_networking = 1;
                  }
               }
               if ($table_fusioninventory_networking == "1") {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id=FUSIONINVENTORY_12.id ";
               } else if ($table_networking_ports == "1") {
                  return " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON glpi_networkports.id = FUSIONINVENTORY_21.end1 OR glpi_networkports.id = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = glpi_networkports.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               } else {
                  return " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_20 ON (FUSIONINVENTORY_20.items_id = glpi_computers.id AND FUSIONINVENTORY_20.itemtype='".PRINTER_TYPE."') ".
                   " LEFT JOIN glpi_networkports_networkports AS FUSIONINVENTORY_21 ON FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end1 OR FUSIONINVENTORY_20.id = FUSIONINVENTORY_21.end2 ".
                     " LEFT JOIN glpi_networkports AS FUSIONINVENTORY_22 ON FUSIONINVENTORY_22.id = CASE WHEN FUSIONINVENTORY_21.end1 = FUSIONINVENTORY_20.id THEN FUSIONINVENTORY_21.end2 ELSE FUSIONINVENTORY_21.end1 END ";
               }
               break;

			}
			break;


		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case 'PluginFusioninventoryNetworkport2' :
			switch ($new_table.".".$linkfield) {

				// ** Location of switch
				case "glpi_locations.networkports_id" :
					return " LEFT JOIN glpi_networkports ON (glpi_plugin_fusioninventory_networkports.networkports_id = glpi_networkports.id) ".
						" LEFT JOIN glpi_networkequipments ON glpi_networkports.items_id = glpi_networkequipments.id".
						" LEFT JOIN glpi_locations ON glpi_locations.id = glpi_networkequipments.location";
					break;

			}
			break;

		// * range IP list (plugins/fusioninventory/front/iprange.php)
		case 'PluginFusioninventoryIPRange' :
			switch ($new_table.".".$linkfield) {

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_discovery" :
					return " LEFT JOIN glpi_plugin_fusioninventory_agents ON (glpi_plugin_fusioninventory_agents.id = glpi_plugin_fusioninventory_ipranges.plugin_fusioninventory_agents_id_discovery) ";
					break;

            case "glpi_plugin_fusioninventory_agents.plugin_fusioninventory_agents_id_query" :
               return " LEFT JOIN glpi_plugin_fusioninventory_agents AS gpta ON (glpi_plugin_fusioninventory_ipranges.plugin_fusioninventory_agents_id_query = gpta.id) ";
               break;
            

			}
			break;

      // * ports updates list (report/switch_ports.history.php)
		case 'PluginFusioninventoryNetworkPortLog' :
         return " LEFT JOIN `glpi_networkports` ON ( `glpi_networkports`.`id` = `glpi_plugin_fusioninventory_networkportlogs`.`networkports_id` ) ";
			break;
	}
	return "";
}



function plugin_fusioninventory_addOrderBy($type,$id,$order,$key=0) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$id]["table"];
	$field=$SEARCH_OPTION[$type][$id]["field"];

//	echo "ORDER BY : ".$table.".".$field;

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
				case "glpi_networkequipments.device" :
					return " ORDER BY FUSIONINVENTORY_12.items_id $order ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networkports.id" :
					return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
					break;

			}
			break;

		// * Networking List (front/networking.php)
		case NETWORKING_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - last inventory
				case "glpi_plugin_fusioninventory_networkequipments.networkequipments_id" :
					return " ORDER BY glpi_plugin_fusioninventory_networkequipments.last_fusioninventory_update $order ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					return " ORDER BY glpi_plugin_fusioninventory_snmpmodels.name $order ";
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
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					return " ORDER BY glpi_plugin_fusioninventory_snmpmodels.name $order ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.id" :
					return " ORDER BY glpi_plugin_fusioninventory_configsnmpsecurities.name $order ";
					break;

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               return " ORDER BY FUSIONINVENTORY_12.items_id $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

			}
			break;

		// * Unknown mac addresses connectd on switch - report (plugins/fusioninventory/report/unknown_mac.php)
		case 'PluginFusioninventoryUnknownDevice' :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
            case "glpi_networkequipments.device" :
               return " ORDER BY FUSIONINVENTORY_12.items_id $order ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
               return " ORDER BY FUSIONINVENTORY_22.".$field." $order ";
               break;

			}
			break;

		// * Ports date connection - report (plugins/fusioninventory/report/ports_date_connections.php)
		case 'PluginFusioninventoryNetworkport2' :
			switch ($table.".".$field) {

				// ** Location of switch
				case "glpi_locations.id" :
					return " ORDER BY glpi_locations.name $order ";
					break;

			}
			break;

		// * range IP list (plugins/fusioninventory/front/iprange.php)
		case 'PluginFusioninventoryIPRange' :
			switch ($table.".".$field) {
			
				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.id" :
					return " ORDER BY glpi_plugin_fusioninventory_agents.name $order ";
					break;

			}
			break;

		// * Detail of ports history (plugins/fusioninventory/report/switch_ports.history.php)
		case 'PluginFusioninventoryNetworkPortLog' :
			switch ($table.".".$field) {

				// ** Display switch and Port
				case "glpi_plugin_fusioninventory_networkportlogs.id" :
					return " ORDER BY glpi_plugin_fusioninventory_networkportlogs.id $order ";
					break;
				case "glpi_networkports.id" :
					return " ORDER BY glpi_networkequipments.name,glpi_networkports.name $order ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_networkportlogs.field" :
					return " ORDER BY glpi_plugin_fusioninventory_networkportlogs.field $order ";
					break;

				// ** Display Old Value (before changement of value)
				case "glpi_plugin_fusioninventory_networkportlogs.old_value" :
					return " ORDER BY glpi_plugin_fusioninventory_networkportlogs.old_value $order ";
					break;

				// ** Display New Value (new value modified)
				case "glpi_plugin_fusioninventory_networkportlogs.new_value" :
					return " ORDER BY glpi_plugin_fusioninventory_networkportlogs.new_value $order ";
					break;

				case "glpi_plugin_fusioninventory_networkportlogs.date_mod" :
				return " ORDER BY glpi_plugin_fusioninventory_networkportlogs.date_mod $order ";
						break;

			}
	}
	return "";
}



function plugin_fusioninventory_addWhere($link,$nott,$type,$id,$val) {
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$id]["table"];
	$field=$SEARCH_OPTION[$type][$id]["field"];

//	echo "add where : ".$table.".".$field."<br/>";
	$SEARCH=makeTextSearch($val,$nott);

	switch ($type) {
		// * Computer List (front/computer.php)
		case COMPUTER_TYPE :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
				case "glpi_plugin_fusioninventory_networkequipments.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_12.items_id IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR FUSIONINVENTORY_12.items_id IS NOT NULL";
					}
					return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - switch port
				case "glpi_plugin_fusioninventory_networkports.id" :
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
				case "glpi_plugin_fusioninventory_networkequipments.networkequipments_id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.last_fusioninventory_update IS NOT NULL";
					}
					return $link." ($table.last_fusioninventory_update  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP models
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_networkequipments.plugin_fusioninventory_snmpauths_id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_fusioninventory_configsnmpsecurities.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_plugin_fusioninventory_configsnmpsecurities.name IS NOT NULL";
					}
					return $link." (glpi_plugin_fusioninventory_configsnmpsecurities.name  LIKE '%".$val."%' $ADD ) ";
					break;

            // ** FusionInventory - CPU
            case "glpi_plugin_fusioninventory_networkequipments.cpu":

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
				case "glpi_plugin_fusioninventory_snmpmodels.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - SNMP authentification
				case "glpi_plugin_fusioninventory_configsnmpsecurities.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
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
		case 'PluginFusioninventoryUnknownDevice' :
			switch ($table.".".$field) {

				// ** FusionInventory - switch
            case "glpi_plugin_fusioninventory_networkequipments.id" :
               $ADD = "";
               if ($nott=="0"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NULL";
               } else if ($nott=="1"&&$val=="NULL") {
                  $ADD=" OR FUSIONINVENTORY_12.items_id IS NOT NULL";
               }
               return $link." (FUSIONINVENTORY_13.name  LIKE '%".$val."%' $ADD ) ";
               break;

            // ** FusionInventory - switch port
            case "glpi_plugin_fusioninventory_networkports.id" :
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
		case 'PluginFusioninventoryNetworkport2' :
			switch ($table.".".$field) {

				// ** Name and link of networking device (switch)
				case "glpi_plugin_fusioninventory_networkports.id" :
				break;

				// ** Name and link of port of networking device (port of switch)
				case "glpi_plugin_fusioninventory_networkports.networkports_id" :
					break;

				// ** Location of switch
				case "glpi_locations.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR glpi_networkequipments.location IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR glpi_networkequipments.location IS NOT NULL";
					}
					if ($val == "0") {
						return $link." (glpi_networkequipments.location >= -1 ) ";
               }
					return $link." (glpi_networkequipments.location = '".$val."' $ADD ) ";
					break;

				case "glpi_plugin_fusioninventory_networkports.lastup" :
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

		// * range IP list (plugins/fusioninventory/front/iprange.php)
		case 'PluginFusioninventoryIPRange' :
			switch ($table.".".$field) {

				// ** Name of range IP and link to form
				case "glpi_plugin_fusioninventory_ipranges.name" :
					break;

				// ** Agent name associed to IP range and link to agent form
				case "glpi_plugin_fusioninventory_agents.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.name IS NULL";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.name IS NOT NULL";
					}
					return $link." ($table.name  LIKE '%".$val."%' $ADD ) ";
					break;

			}

         switch ($table.".".$SEARCH_OPTION[$type][$id]["linkfield"]) {

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
		case 'PluginFusioninventoryNetworkPortLog' :
			switch ($table.".".$field) {

				// ** Display switch and Port
				case "glpi_networkports.id" :
					$ADD = "";
					if ($nott=="0"&&$val=="NULL") {
						$ADD=" OR $table.id IS NULL ";
					} else if ($nott=="1"&&$val=="NULL") {
						$ADD=" OR $table.id IS NOT NULL ";
					}
					return $link." ($table.id = '".$val."' $ADD ) ";
					break;

				// ** Display GLPI field of device
				case "glpi_plugin_fusioninventory_networkportlogs.field" :
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
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkequipments`
                             WHERE `networkequipments_id`='".$parm["id"]."';";
				$DB->query($query_delete);

				$query_select = "SELECT `glpi_plugin_fusioninventory_networkports`.`id`
                             FROM `glpi_plugin_fusioninventory_networkports`
                                  LEFT JOIN `glpi_networkports`
                                            ON `glpi_networkports`.`id` = `networkports_id`
                             WHERE `items_id`='".$parm["id"]."'
                                   AND `itemtype`='".NETWORKING_TYPE."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkports`
                                WHERE `id`='".$data["id"]."';";
					$DB->query($query_delete);
				}

				$query_select = "SELECT `glpi_plugin_fusioninventory_networkequipmentips`.`id`
                             FROM `glpi_plugin_fusioninventory_networkequipmentips`
                                  LEFT JOIN `glpi_networkequipments`
                                            ON `glpi_networkequipments`.`id` = `networkequipments_id`
                             WHERE `networkequipments_id`='".$parm["id"]."';";
				$result=$DB->query($query_select);
				while ($data=$DB->fetch_array($result)) {
					$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkequipmentips`
                                WHERE `id`='".$data["id"]."';";
					$DB->query($query_delete);
				}
            break;

			case PRINTER_TYPE :
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printers`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printercartridges`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
				$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_printerlogs`
                             WHERE `printers_id`='".$parm["id"]."';";
				$DB->query($query_delete);
            break;

         case 'PluginFusioninventoryUnknownDevice' :
            // Delete ports and connections if exists
            $np=new NetworkPort;
            $nn = new NetworkPort_NetworkPort();
            $query = "SELECT `id`
                      FROM `glpi_networkports`
                      WHERE `items_id` = '".$parm["id"]."'
                            AND `itemtype` = 'PluginFusioninventoryUnknownDevice';";
            $result = $DB->query($query);
            while ($data = $DB->fetch_array($result)) {
               if ($nn->getFromDBForNetworkPort($data['id'])) {
                  $nn->delete($data);
               }
               $np->delete(array("id"=>$data["id"]));
            }
            break;

         case COMPUTER_TYPE :
            // Delete link between computer and agent fusion
            $query = "UPDATE `glpi_plugin_fusioninventory_agents`
                        SET `items_id` = '0'
                           AND `itemtype` = '0'
                        WHERE `items_id` = '".$parm["id"]."'
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
            	$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkports`
                  WHERE `networkports_id`='".$parm["id"]."';";
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
         $id=$parm['id'];
         $fieldsToLock=$parm['updates'];
         $lockables = PluginFusioninventoryLockable::getLockableFields('', $type);
         $fieldsToLock = array_intersect($fieldsToLock, $lockables); // do not lock unlockable fields
         PluginFusioninventoryLock::addLocks($type, $id, $fieldsToLock);
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
            if ($parm["input"]["itemtype"] != 'PluginFusioninventoryUnknownDevice') {
               // Search in DB
               $np = new NetworkPort;
               $nw = new NetworkPort_NetworkPort;
               $pfiud = new PluginFusioninventoryUnknownDevice;
               $a_ports = $np->find("`mac`='".$parm["input"]["mac"]."' AND `itemtype`='PluginFusioninventoryUnknownDevice' ");
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
                     if (count($np->find("`items_id`='".$port_infos['items_id']."' AND `itemtype`='PluginFusioninventoryUnknownDevice' ")) == "0") {
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
