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

global $LANG,$LANGTRACKER,$TRACKER_MAPPING;

// ----------------------------------------------------------------------
//NETWORK MAPPING MAPPING
// ----------------------------------------------------------------------
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['field'] = 'location';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['name'] = $LANGTRACKER["mapping"][1];
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['field'] = 'firmware';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['name'] = $LANGTRACKER["mapping"][2];
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['firmware']['dropdown'] = 'glpi_dropdown_firmware';

$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['field'] = 'uptime';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['name'] = $LANGTRACKER["mapping"][3];
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['uptime']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['field'] = 'ifmtu';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['name'] = $LANGTRACKER["mapping"][4];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifmtu']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['field'] = 'ifspeed';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['name'] = $LANGTRACKER["mapping"][5];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifspeed']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['field'] = 'ifinternalstatus';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['name'] = $LANGTRACKER["mapping"][6];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['field'] = 'iflastchange';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['name'] = $LANGTRACKER["mapping"][7];
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['iflastchange']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['field'] = 'ifinoctets';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['name'] = $LANGTRACKER["mapping"][8];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinoctets']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['field'] = 'ifoutoctets';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['name'] = $LANGTRACKER["mapping"][9];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifoutoctets']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['field'] = 'ifinerrors';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['name'] = $LANGTRACKER["mapping"][10];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifinerrors']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['field'] = 'ifouterrors';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['name'] = $LANGTRACKER["mapping"][11];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifouterrors']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['table'] = 'glpi_plugin_tracker_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['field'] = 'cpu';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['name'] = $LANGTRACKER["mapping"][12];
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['cpu']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['table'] = 'glpi_networking';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['field'] = 'serial';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['name'] = $LANGTRACKER["mapping"][13];
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['serial']['dropdown'] = '';

$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['table'] = 'glpi_plugin_tracker_networking_ports';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['field'] = 'ifstatus';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['name'] = $LANGTRACKER["mapping"][14];
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['type'] = 'text';
$TRACKER_MAPPING[NETWORKING_TYPE]['ifstatus']['dropdown'] = '';

?>
