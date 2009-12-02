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
// Original Author of file: MAZZONI Vincent
// Purpose of file: mapping table fields with constants
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

global $LANG, $TRACKER_MAPPING_FIELDS;
//global $LANG,$TRACKER_MAPPING,$TRACKER_MAPPING_DISCOVERY;
//global $TRACKER_MAPPING,$TRACKER_MAPPING_DISCOVERY;

$TRACKER_MAPPING_FIELDS['name']           = $LANG['plugin_tracker']["mapping_fields"][0];
$TRACKER_MAPPING_FIELDS['contact']        = $LANG['plugin_tracker']["mapping_fields"][1];
$TRACKER_MAPPING_FIELDS['comments']       = $LANG['plugin_tracker']["mapping_fields"][2];
?>
