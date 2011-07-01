<?php
/*
 * @version $Id: fieldunicity.form.php 14033 2011-03-06 16:14:51Z yllen $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------


define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

$dropdown = new PluginFusioninventoryCredentialIp();
if (empty($_POST['plugin_fusioninventory_credentials_id'])) {
   $_POST['plugin_fusioninventory_credentials_id'] = -1;
}
include (GLPI_ROOT . "/front/dropdown.common.form.php");

if (strstr($_SERVER['HTTP_REFERER'], "wizard.php")) {
   glpi_header($_SERVER['HTTP_REFERER']."&id=".$_GET['id']);
}

?>