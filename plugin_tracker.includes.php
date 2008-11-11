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

include_once ("plugin_tracker.define.php");
include_once ("plugin_tracker.mibs.define.php");

include_once ("inc/plugin_tracker.classes.php");
include_once ("inc/plugin_tracker.snmp.classes.php");
include_once ("inc/plugin_tracker.importexport.class.php");
include_once ("inc/plugin_tracker.log.classes.php");

include_once ("inc/plugin_tracker.functions_auth.php");
include_once ("inc/plugin_tracker.functions_db.php");
include_once ("inc/plugin_tracker.functions_dropdown.php");
include_once ("inc/plugin_tracker.functions_ifmac.php");
//include_once ("inc/plugin_tracker.functions_rights.php");
include_once ("inc/plugin_tracker.functions_setup.php");

include_once ("inc/plugin_tracker.functions_cron.php");

include_once ("inc/plugin_tracker.functions_display.php");

include_once("inc/plugin_tracker.thread.class.php");

include_once("inc/plugin_tracker.computerhistory.class.php");
?>