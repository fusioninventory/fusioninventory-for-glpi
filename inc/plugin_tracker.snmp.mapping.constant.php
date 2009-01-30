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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

global $LANG,$TRACKER_MAPPING,$TRACKER_MAPPING_DISCOVERY;

// ----------------------------------------------------------------------
//NETWORK MAPPING MAPPING
// ----------------------------------------------------------------------
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['field'] = 'location';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['name'] = $LANG['plugin_tracker']["mapping"][1];
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['field'] = 'firmware';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['name'] = $LANG['plugin_tracker']["mapping"][2];
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['dropdown'] = 'glpi_dropdown_firmware';

$TRACKER_MAPPING[NETWORKING_TYPE]['contact']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['contact']['field'] = 'contact';
$TRACKER_MAPPING[NETWORKING_TYPE]['contact']['name'] = $LANG['plugin_tracker']["mapping"][403];
$TRACKER_MAPPING[NETWORKING_TYPE]['contact']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['contact']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['comments']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['comments']['field'] = 'comments';
$TRACKER_MAPPING[NETWORKING_TYPE]['comments']['name'] = $LANG['plugin_tracker']["mapping"][404];
$TRACKER_MAPPING[NETWORKING_TYPE]['comments']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['comments']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['field'] = 'uptime';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['name'] = $LANG['plugin_tracker']["mapping"][3];
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['field'] = 'cpu';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['name'] = $LANG['plugin_tracker']["mapping"][12];
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['cpuuser']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpuuser']['field'] = 'cpu';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpuuser']['name'] = $LANG['plugin_tracker']["mapping"][401];
$TRACKER_MAPPING[NETWORKING_TYPE]['cpuuser']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpuuser']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['cpusystem']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpusystem']['field'] = 'cpu';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpusystem']['name'] = $LANG['plugin_tracker']["mapping"][402];
$TRACKER_MAPPING[NETWORKING_TYPE]['cpusystem']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpusystem']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['field'] = 'serial';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['name'] = $LANG['plugin_tracker']["mapping"][13];
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['name']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['name']['field'] = 'name';
$TRACKER_MAPPING[NETWORKING_TYPE]['name']['name'] = $LANG['plugin_tracker']["mapping"][20];
$TRACKER_MAPPING[NETWORKING_TYPE]['name']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['name']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ram']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['ram']['field'] = 'ram';
$TRACKER_MAPPING[NETWORKING_TYPE]['ram']['name'] = $LANG['plugin_tracker']["mapping"][21];
$TRACKER_MAPPING[NETWORKING_TYPE]['ram']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ram']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['memory']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['memory']['field'] = 'memory';
$TRACKER_MAPPING[NETWORKING_TYPE]['memory']['name'] = $LANG['plugin_tracker']["mapping"][22];
$TRACKER_MAPPING[NETWORKING_TYPE]['memory']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['memory']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['vtpVlanName']['table'] = '';
$TRACKER_MAPPING[NETWORKING_TYPE]['vtpVlanName']['field'] = '';
$TRACKER_MAPPING[NETWORKING_TYPE]['vtpVlanName']['name'] = $LANG['plugin_tracker']["mapping"][19];
$TRACKER_MAPPING[NETWORKING_TYPE]['vtpVlanName']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['vtpVlanName']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['field'] = 'model';
$TRACKER_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['name'] = $LANG['plugin_tracker']["mapping"][17];
$TRACKER_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['dropdown'] = 'glpi_dropdown_model_networking';






// Networking Ports

$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['field'] = 'ifmtu';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['name'] = $LANG['plugin_tracker']["mapping"][4];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['field'] = 'ifspeed';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['name'] = $LANG['plugin_tracker']["mapping"][5];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['field'] = 'ifinternalstatus';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['name'] = $LANG['plugin_tracker']["mapping"][6];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['field'] = 'iflastchange';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['name'] = $LANG['plugin_tracker']["mapping"][7];
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['field'] = 'ifinoctets';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['name'] = $LANG['plugin_tracker']["mapping"][8];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['field'] = 'ifoutoctets';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['name'] = $LANG['plugin_tracker']["mapping"][9];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['field'] = 'ifinerrors';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['name'] = $LANG['plugin_tracker']["mapping"][10];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['field'] = 'ifouterrors';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['name'] = $LANG['plugin_tracker']["mapping"][11];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['field'] = 'ifstatus';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['name'] = $LANG['plugin_tracker']["mapping"][14];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['field'] = 'ifmac';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_tracker']["mapping"][15];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifName']['table'] = 'glpi_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifName']['field'] = 'name';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifName']['name'] = $LANG['plugin_tracker']["mapping"][16];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifName']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifName']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifType']['table'] = '';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifType']['field'] = '';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifType']['name'] = $LANG['plugin_tracker']["mapping"][18];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifType']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifType']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifdescr']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifdescr']['field'] = 'ifdescr';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifdescr']['name'] = $LANG['plugin_tracker']["mapping"][23];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifdescr']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifdescr']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['portDuplex']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['portDuplex']['field'] = 'portduplex';
$TRACKER_MAPPING[NETWORKING_TYPE]['portDuplex']['name'] = $LANG['plugin_tracker']["mapping"][33];
$TRACKER_MAPPING[NETWORKING_TYPE]['portDuplex']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['portDuplex']['dropdown'] = '';



// Printers

$TRACKER_MAPPING[PRINTER_TYPE]['model']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['model']['field'] = 'model';
$TRACKER_MAPPING[PRINTER_TYPE]['model']['name'] = $LANG['plugin_tracker']["mapping"][25];
$TRACKER_MAPPING[PRINTER_TYPE]['model']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['model']['dropdown'] = 'glpi_dropdown_model_printers';

$TRACKER_MAPPING[PRINTER_TYPE]['serial']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['serial']['field'] = 'serial';
$TRACKER_MAPPING[PRINTER_TYPE]['serial']['name'] = $LANG['plugin_tracker']["mapping"][27];
$TRACKER_MAPPING[PRINTER_TYPE]['serial']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['serial']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['contact']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['contact']['field'] = 'contact';
$TRACKER_MAPPING[PRINTER_TYPE]['contact']['name'] = $LANG['plugin_tracker']["mapping"][405];
$TRACKER_MAPPING[PRINTER_TYPE]['contact']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['contact']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['comments']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['comments']['field'] = 'comments';
$TRACKER_MAPPING[PRINTER_TYPE]['comments']['name'] = $LANG['plugin_tracker']["mapping"][406];
$TRACKER_MAPPING[PRINTER_TYPE]['comments']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['comments']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['name']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['name']['field'] = 'name';
$TRACKER_MAPPING[PRINTER_TYPE]['name']['name'] = $LANG['plugin_tracker']["mapping"][24];
$TRACKER_MAPPING[PRINTER_TYPE]['name']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['name']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['memory']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['memory']['field'] = 'ramSize';
$TRACKER_MAPPING[PRINTER_TYPE]['memory']['name'] = $LANG['plugin_tracker']["mapping"][26];
$TRACKER_MAPPING[PRINTER_TYPE]['memory']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['memory']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['location']['table'] = 'glpi_printers';
$TRACKER_MAPPING[PRINTER_TYPE]['location']['field'] = 'location';
$TRACKER_MAPPING[PRINTER_TYPE]['location']['name'] = $LANG['plugin_tracker']["mapping"][56];
$TRACKER_MAPPING[PRINTER_TYPE]['location']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['name'] = $LANG['plugin_tracker']["mapping"][34];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['shortname'] = $LANG['plugin_tracker']["mapping"][134];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblack']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['name'] = $LANG['plugin_tracker']["mapping"][59];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][60];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['name'] = $LANG['plugin_tracker']["mapping"][35];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['shortname'] = $LANG['plugin_tracker']["mapping"][135];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesblackphoto']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['name'] = $LANG['plugin_tracker']["mapping"][36];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['shortname'] = $LANG['plugin_tracker']["mapping"][136];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyan']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['name'] = $LANG['plugin_tracker']["mapping"][61];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][62];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['name'] = $LANG['plugin_tracker']["mapping"][37];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['shortname'] = $LANG['plugin_tracker']["mapping"][137];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellow']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['name'] = $LANG['plugin_tracker']["mapping"][63];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][64];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesyellowREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['name'] = $LANG['plugin_tracker']["mapping"][38];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['shortname'] = $LANG['plugin_tracker']["mapping"][138];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagenta']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['name'] = $LANG['plugin_tracker']["mapping"][65];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][66];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentaREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['name'] = $LANG['plugin_tracker']["mapping"][39];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['shortname'] = $LANG['plugin_tracker']["mapping"][139];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlight']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['name'] = $LANG['plugin_tracker']["mapping"][67];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][68];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgescyanlightREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['name'] = $LANG['plugin_tracker']["mapping"][40];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['shortname'] = $LANG['plugin_tracker']["mapping"][140];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalight']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['name'] = $LANG['plugin_tracker']["mapping"][69];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][70];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmagentalightREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['name'] = $LANG['plugin_tracker']["mapping"][41];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['shortname'] = $LANG['plugin_tracker']["mapping"][141];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductor']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['name'] = $LANG['plugin_tracker']["mapping"][71];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][72];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['name'] = $LANG['plugin_tracker']["mapping"][42];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['shortname'] = $LANG['plugin_tracker']["mapping"][142];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblack']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['name'] = $LANG['plugin_tracker']["mapping"][73];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][74];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorblackREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['name'] = $LANG['plugin_tracker']["mapping"][43];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['shortname'] = $LANG['plugin_tracker']["mapping"][143];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolor']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['name'] = $LANG['plugin_tracker']["mapping"][75];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][76];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcolorREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['name'] = $LANG['plugin_tracker']["mapping"][44];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['shortname'] = $LANG['plugin_tracker']["mapping"][144];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyan']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['name'] = $LANG['plugin_tracker']["mapping"][77];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][78];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductorcyanREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['name'] = $LANG['plugin_tracker']["mapping"][45];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['shortname'] = $LANG['plugin_tracker']["mapping"][145];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellow']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['name'] = $LANG['plugin_tracker']["mapping"][79];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][80];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductoryellowREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['name'] = $LANG['plugin_tracker']["mapping"][46];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['shortname'] = $LANG['plugin_tracker']["mapping"][146];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagenta']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['name'] = $LANG['plugin_tracker']["mapping"][81];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][82];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesphotoconductormagentaREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['name'] = $LANG['plugin_tracker']["mapping"][47];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['shortname'] = $LANG['plugin_tracker']["mapping"][147];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblack']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['name'] = $LANG['plugin_tracker']["mapping"][83];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][84];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertblackREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['name'] = $LANG['plugin_tracker']["mapping"][48];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['shortname'] = $LANG['plugin_tracker']["mapping"][148];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyan']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['name'] = $LANG['plugin_tracker']["mapping"][85];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][86];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertcyanREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['name'] = $LANG['plugin_tracker']["mapping"][49];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['shortname'] = $LANG['plugin_tracker']["mapping"][149];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellow']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['name'] = $LANG['plugin_tracker']["mapping"][87];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][88];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertyellowREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['name'] = $LANG['plugin_tracker']["mapping"][50];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['shortname'] = $LANG['plugin_tracker']["mapping"][150];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagenta']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['name'] = $LANG['plugin_tracker']["mapping"][89];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][90];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesunittransfertmagentaREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['name'] = $LANG['plugin_tracker']["mapping"][51];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['shortname'] = $LANG['plugin_tracker']["mapping"][151];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswaste']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['name'] = $LANG['plugin_tracker']["mapping"][91];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][92];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgeswasteREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['name'] = $LANG['plugin_tracker']["mapping"][52];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['shortname'] = $LANG['plugin_tracker']["mapping"][152];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['name'] = $LANG['plugin_tracker']["mapping"][93];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][94];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuserREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['name'] = $LANG['plugin_tracker']["mapping"][53];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['shortname'] = $LANG['plugin_tracker']["mapping"][153];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleaner']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['name'] = $LANG['plugin_tracker']["mapping"][95];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][96];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesbeltcleanerREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['name'] = $LANG['plugin_tracker']["mapping"][400];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekit']['shortname'] = $LANG['plugin_tracker']["mapping"][156];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesfuser']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['name'] = $LANG['plugin_tracker']["mapping"][98];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitMAX']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['table'] = 'glpi_plugin_tracker_printers_cartridges';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['field'] = 'state';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['name'] = $LANG['plugin_tracker']["mapping"][99];
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['cartridgesmaintenancekitREMAIN']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['table'] = 'glpi_plugin_tracker_printers_history';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['field'] = 'pages_total';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['name'] = $LANG['plugin_tracker']["mapping"][28];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['shortname'] = $LANG['plugin_tracker']["mapping"][128];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['table'] = 'glpi_plugin_tracker_printers_history';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['field'] = 'pages_n_b';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['name'] = $LANG['plugin_tracker']["mapping"][29];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['shortname'] = $LANG['plugin_tracker']["mapping"][129];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['table'] = 'glpi_plugin_tracker_printers_history';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['field'] = 'pages_color';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['name'] = $LANG['plugin_tracker']["mapping"][30];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['shortname'] = $LANG['plugin_tracker']["mapping"][130];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['table'] = 'glpi_plugin_tracker_printers_history';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['field'] = 'pages_recto_verso';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['name'] = $LANG['plugin_tracker']["mapping"][54];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['shortname'] = $LANG['plugin_tracker']["mapping"][154];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['table'] = 'glpi_plugin_tracker_printers_history';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['field'] = 'scanned';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['name'] = $LANG['plugin_tracker']["mapping"][55];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['shortname'] = $LANG['plugin_tracker']["mapping"][155];
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
$TRACKER_MAPPING[PRINTER_TYPE]['ifPhysAddress']['field'] = 'ifmac';
$TRACKER_MAPPING[PRINTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_tracker']["mapping"][58];
$TRACKER_MAPPING[PRINTER_TYPE]['ifPhysAddress']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['ifPhysAddress']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['ifName']['table'] = 'glpi_networking_ports';
$TRACKER_MAPPING[PRINTER_TYPE]['ifName']['field'] = 'name';
$TRACKER_MAPPING[PRINTER_TYPE]['ifName']['name'] = $LANG['plugin_tracker']["mapping"][57];
$TRACKER_MAPPING[PRINTER_TYPE]['ifName']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['ifName']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['ifaddr']['table'] = 'glpi_networking_ports';
$TRACKER_MAPPING[PRINTER_TYPE]['ifaddr']['field'] = 'ifaddr';
$TRACKER_MAPPING[PRINTER_TYPE]['ifaddr']['name'] = $LANG['plugin_tracker']["mapping"][407];
$TRACKER_MAPPING[PRINTER_TYPE]['ifaddr']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['ifaddr']['dropdown'] = '';

$TRACKER_MAPPING[PRINTER_TYPE]['ifType']['table'] = '';
$TRACKER_MAPPING[PRINTER_TYPE]['ifType']['field'] = '';
$TRACKER_MAPPING[PRINTER_TYPE]['ifType']['name'] = $LANG['plugin_tracker']["mapping"][97];
$TRACKER_MAPPING[PRINTER_TYPE]['ifType']['type'] = 'text';
$TRACKER_MAPPING[PRINTER_TYPE]['ifType']['dropdown'] = '';


// *************************** TYPE of Devices for discovery *************************** //

$TRACKER_MAPPING_DISCOVERY['discovery'][0]['object'] = 'prtGeneralConfigChanges';
$TRACKER_MAPPING_DISCOVERY['discovery'][0]['oid'] = '.1.3.6.1.2.1.43.5.1.1.1.1';
$TRACKER_MAPPING_DISCOVERY['discovery'][0]['type'] = PRINTER_TYPE;

$TRACKER_MAPPING_DISCOVERY['discovery'][1]['object'] = 'systemBridgeAdressEntry';
$TRACKER_MAPPING_DISCOVERY['discovery'][1]['oid'] = '.1.3.6.1.2.1.17.1.1.0';
$TRACKER_MAPPING_DISCOVERY['discovery'][1]['type'] = NETWORKING_TYPE;

$TRACKER_MAPPING_DISCOVERY['discovery'][2]['object'] = 'upsIdentManufacturer';
$TRACKER_MAPPING_DISCOVERY['discovery'][2]['oid'] = '.1.3.6.1.2.1.33.1.1.1.0';
$TRACKER_MAPPING_DISCOVERY['discovery'][2]['type'] = PERIPHERAL_TYPE;

$TRACKER_MAPPING_DISCOVERY['discovery'][3]['object'] = 'hrSWRunName';
$TRACKER_MAPPING_DISCOVERY['discovery'][3]['oid'] = '.1.3.6.1.2.1.25.4.2.1.2.1';
$TRACKER_MAPPING_DISCOVERY['discovery'][3]['type'] = COMPUTER_TYPE;

?>