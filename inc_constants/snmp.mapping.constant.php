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
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['field'] = 'location';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][1];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['dropdown'] = 'glpi_locations';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['field'] = 'firmware';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['name'] = $LANG['plugin_fusioninventory']["mapping"][2];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['dropdown'] = 'glpi_networkequipmentsfirmwares';

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

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['field'] = 'contact';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['name'] = $LANG['plugin_fusioninventory']["mapping"][403];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comment']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comment']['field'] = 'comment';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comment']['name'] = $LANG['plugin_fusioninventory']["mapping"][404];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comment']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comment']['dropdown'] = '';

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

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['field'] = 'serial';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][13];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['field'] = 'otherserial';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['name'] = $LANG['plugin_fusioninventory']["mapping"][419];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['name'] = $LANG['plugin_fusioninventory']["mapping"][20];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['table'] = 'glpi_networkequipments';
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

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['field'] = 'model';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['name'] = $LANG['plugin_fusioninventory']["mapping"][17];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['dropdown'] = 'glpi_dropdown_model_networking';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['table'] = 'glpi_networkequipments';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['field'] = 'mac';
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

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['table'] = 'glpi_networkports';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['field'] = 'mac';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][15];
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['table'] = 'glpi_networkports';
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
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['field'] = 'manufacturers_id';
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

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comment']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comment']['field'] = 'comment';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comment']['name'] = $LANG['plugin_fusioninventory']["mapping"][406];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comment']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comment']['dropdown'] = '';

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
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['field'] = 'memory_size';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['name'] = $LANG['plugin_fusioninventory']["mapping"][26];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['table'] = 'glpi_printers';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['field'] = 'location';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][56];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['dropdown'] = 'glpi_locations';

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

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['table'] = 'glpi_networkports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['field'] = 'mac';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][58];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['table'] = 'glpi_networkports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['field'] = 'name';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['name'] = $LANG['plugin_fusioninventory']["mapping"][57];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['dropdown'] = '';

$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ip']['table'] = 'glpi_networkports';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ip']['field'] = 'ip';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ip']['name'] = $LANG['plugin_fusioninventory']["mapping"][407];
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ip']['type'] = 'text';
$FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ip']['dropdown'] = '';

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



// Computer :

$FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['serial']['field'] = 'serial';
$FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][13];

$FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifPhysAddress']['field'] = 'mac';
$FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][15];


?>