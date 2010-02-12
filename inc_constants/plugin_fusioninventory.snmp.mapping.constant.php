<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

global $LANG,$FUSIONINVENTORY_MAPPING,$FUSIONINVENTORY_MAPPING_DISCOVERY;

// ----------------------------------------------------------------------
//NETWORK MAPPING MAPPING
// ----------------------------------------------------------------------
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['field'] = 'location';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][1];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['field'] = 'firmware';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['name'] = $LANG['plugin_fusioninventory']["mapping"][2];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['dropdown'] = 'glpi_dropdown_firmware';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['name'] = $LANG['plugin_fusioninventory']["mapping"][2]." 1";
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['name'] = $LANG['plugin_fusioninventory']["mapping"][2]." 2";
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['field'] = 'contact';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['name'] = $LANG['plugin_fusioninventory']["mapping"][403];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['field'] = 'comments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['name'] = $LANG['plugin_fusioninventory']["mapping"][404];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['table'] = 'glpi_plugin_fusioninventory_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['field'] = 'uptime';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['name'] = $LANG['plugin_fusioninventory']["mapping"][3];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['table'] = 'glpi_plugin_fusioninventory_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['field'] = 'cpu';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['name'] = $LANG['plugin_fusioninventory']["mapping"][12];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['table'] = 'glpi_plugin_fusioninventory_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['field'] = 'cpu';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['name'] = $LANG['plugin_fusioninventory']["mapping"][401];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['table'] = 'glpi_plugin_fusioninventory_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['field'] = 'cpu';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['name'] = $LANG['plugin_fusioninventory']["mapping"][402];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['field'] = 'serial';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][13];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['field'] = 'otherserial';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['name'] = $LANG['plugin_fusioninventory']["mapping"][419];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['name'] = $LANG['plugin_fusioninventory']["mapping"][20];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['field'] = 'ram';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['name'] = $LANG['plugin_fusioninventory']["mapping"][21];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['table'] = 'glpi_plugin_fusioninventory_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['field'] = 'memory';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['name'] = $LANG['plugin_fusioninventory']["mapping"][22];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['name'] = $LANG['plugin_fusioninventory']["mapping"][19];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['name'] = $LANG['plugin_fusioninventory']["mapping"][430];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['field'] = 'model';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['name'] = $LANG['plugin_fusioninventory']["mapping"][17];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['dropdown'] = 'glpi_dropdown_model_networking';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['table'] = 'glpi_networking';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['field'] = 'ifmac';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][417];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['dropdown'] = '';

// Networking CDP (Walk)
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][409];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['name'] = $LANG['plugin_fusioninventory']["mapping"][410];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][411];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][412];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][413];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['name'] = $LANG['plugin_fusioninventory']["mapping"][414];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][415];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][421];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][422];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['dropdown'] = '';

// Networking Ports

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][408];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['field'] = 'ifmtu';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['name'] = $LANG['plugin_fusioninventory']["mapping"][4];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['field'] = 'ifspeed';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['name'] = $LANG['plugin_fusioninventory']["mapping"][5];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['field'] = 'ifinternalstatus';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][6];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['field'] = 'iflastchange';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['name'] = $LANG['plugin_fusioninventory']["mapping"][7];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['field'] = 'ifinoctets';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['name'] = $LANG['plugin_fusioninventory']["mapping"][8];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['field'] = 'ifoutoctets';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['name'] = $LANG['plugin_fusioninventory']["mapping"][9];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['field'] = 'ifinerrors';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['name'] = $LANG['plugin_fusioninventory']["mapping"][10];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['field'] = 'ifouterrors';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['name'] = $LANG['plugin_fusioninventory']["mapping"][11];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['field'] = 'ifstatus';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][14];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['field'] = 'ifmac';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][15];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['table'] = 'glpi_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['name'] = $LANG['plugin_fusioninventory']["mapping"][16];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['table'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['field'] = '';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['name'] = $LANG['plugin_fusioninventory']["mapping"][18];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['field'] = 'ifdescr';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['name'] = $LANG['plugin_fusioninventory']["mapping"][23];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['field'] = 'portduplex';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['name'] = $LANG['plugin_fusioninventory']["mapping"][33];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['dropdown'] = '';



// Printers

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['field'] = 'model';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['name'] = $LANG['plugin_fusioninventory']["mapping"][25];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['dropdown'] = 'glpi_dropdown_model_printers';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['field'] = 'FK_glpi_enterprise';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['name'] = $LANG['plugin_fusioninventory']["mapping"][420];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['dropdown'] = 'glpi_dropdown_manufacturer';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['field'] = 'serial';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][27];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['field'] = 'contact';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['name'] = $LANG['plugin_fusioninventory']["mapping"][405];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['field'] = 'comments';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['name'] = $LANG['plugin_fusioninventory']["mapping"][406];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['name'] = $LANG['plugin_fusioninventory']["mapping"][24];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['field'] = 'otherserial';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['name'] = $LANG['plugin_fusioninventory']["mapping"][418];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['field'] = 'ramSize';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['name'] = $LANG['plugin_fusioninventory']["mapping"][26];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['field'] = 'location';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][56];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['informations']['name'] = $LANG['plugin_fusioninventory']["mapping"][165];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['informations']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][165];

// NEW CARTRIDGE
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][157];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][157];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2']['name'] = $LANG['plugin_fusioninventory']["mapping"][157]." 2";
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][157]." 2";

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][158];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][158];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][159];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][159];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][160];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][160];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetoner']['name'] = $LANG['plugin_fusioninventory']["mapping"][151];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetoner']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][151];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][134];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][134];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblackphoto']['name'] = $LANG['plugin_fusioninventory']["mapping"][135];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblackphoto']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][135];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][136];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][136];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyanlight']['name'] = $LANG['plugin_fusioninventory']["mapping"][139];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyanlight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][139];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][138];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][138];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagentalight']['name'] = $LANG['plugin_fusioninventory']["mapping"][140];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagentalight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][140];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][137];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][137];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekit']['name'] = $LANG['plugin_fusioninventory']["mapping"][156];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekit']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][156];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][161];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][161];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][162];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][162];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][163];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][163];

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][164];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][164];
















// END NEW CARTRIDGE
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][34];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][134];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblack']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][59];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][60];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['name'] = $LANG['plugin_fusioninventory']["mapping"][35];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][135];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][36];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][136];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyan']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][61];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][62];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][37];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][137];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellow']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][63];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][64];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][38];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][138];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][65];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][66];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['name'] = $LANG['plugin_fusioninventory']["mapping"][39];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][139];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][67];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][68];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['name'] = $LANG['plugin_fusioninventory']["mapping"][40];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][140];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][69];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][70];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['name'] = $LANG['plugin_fusioninventory']["mapping"][41];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][141];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][71];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][72];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][42];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][142];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][73];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][74];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['name'] = $LANG['plugin_fusioninventory']["mapping"][43];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][143];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][75];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][76];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][44];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][144];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][77];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][78];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][45];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][145];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][79];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][80];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][46];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][146];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][81];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][82];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][47];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][147];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][83];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][84];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][48];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][148];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][85];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][86];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][49];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][149];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][87];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][88];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][50];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][150];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][89];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][90];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['name'] = $LANG['plugin_fusioninventory']["mapping"][51];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][151];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswaste']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][91];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][92];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['name'] = $LANG['plugin_fusioninventory']["mapping"][52];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][152];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][93];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][94];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['name'] = $LANG['plugin_fusioninventory']["mapping"][53];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][153];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][95];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][96];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['name'] = $LANG['plugin_fusioninventory']["mapping"][400];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][156];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesfuser']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['name'] = $LANG['plugin_fusioninventory']["mapping"][98];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['dropdown'] = '';
//
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['table'] = 'glpi_plugin_fusioninventory_printers_cartridges';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['field'] = 'state';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['name'] = $LANG['plugin_fusioninventory']["mapping"][99];
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['type'] = 'text';
//$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['dropdown'] = '';

// Printers : Counter pages

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['field'] = 'pages_total';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][28];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][128];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['field'] = 'pages_n_b';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][29];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][129];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['field'] = 'pages_color';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][30];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][130];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['field'] = 'pages_recto_verso';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['name'] = $LANG['plugin_fusioninventory']["mapping"][54];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][154];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['field'] = 'scanned';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][55];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][155];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['field'] = 'pages_total_print';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][423];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1423];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['field'] = 'pages_n_b_print';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][424];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1424];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['field'] = 'pages_color_print';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][425];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1425];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['field'] = 'pages_total_copy';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][426];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1426];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['field'] = 'pages_n_b_copy';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][427];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1427];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['field'] = 'pages_color_copy';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][428];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1428];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['table'] = 'glpi_plugin_fusioninventory_printers_history';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['field'] = 'pages_total_fax';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['name'] = $LANG['plugin_fusioninventory']["mapping"][429];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1429];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['dropdown'] = '';

// Printers : Networking

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['field'] = 'ifmac';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][58];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['table'] = 'glpi_networking_ports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['name'] = $LANG['plugin_fusioninventory']["mapping"][57];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['table'] = 'glpi_networking_ports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['field'] = 'ifaddr';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][407];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['table'] = '';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['field'] = '';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['name'] = $LANG['plugin_fusioninventory']["mapping"][97];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['table'] = '';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['field'] = '';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][416];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['dropdown'] = '';

?>