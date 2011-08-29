<?php
/*
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
// Original Author of file: Alexandre delaunay
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

$PluginFusinvdeployFile = new PluginFusinvdeployFile();

if(isset($_GET['package_id'])){
   $package_id = $_GET['package_id'];
   $render     = $_GET['render'];
}
foreach($_POST as $POST_key => $POST_value) {
   $new_key         = preg_replace('#^'.$render.'#','',$POST_key);
   $_POST[$new_key] = $POST_value;
}
error_log(print_r($_POST, 1));

if (isset ($_POST["id"]) && $_POST['id']){

   $data = array( 'id' => $_POST['id'],
                  'is_p2p' => (($_POST['p2p'] != 'false') ? 1 : 0),
                  'uncompress' => (($_POST['uncompress'] == 'true') ? 1 : 0),
                  'p2p_retention_days' => is_int($_POST['validity']) ? $_POST['validity'] : 0); 
error_log(print_r($data, 1));

   $PluginFusinvdeployFile->update($data);
   echo "{success:true, file:'N/A',msg:\"{$LANG['plugin_fusinvdeploy']['form']['action'][4]}\"}";
}

?>
