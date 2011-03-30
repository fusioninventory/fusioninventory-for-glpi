<?php
/*
 * @version $Id: plugin_callcenter.frontGrid.php 4635 2010-03-26 14:21:15Z SphynXz $
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
// Original Author of file: Anthony Hebert
// Purpose of file:
// ----------------------------------------------------------------------
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

global $DB;

if(isset($_GET['render'])){
   $render = $_GET['render'];
} else {
   exit;
}

foreach($_POST as $POST_key => $POST_value) {
   $new_key = preg_replace('#^'.$render.'#','',$POST_key);
   $_POST[$new_key] = $POST_value;
}

$PluginFusinvdeployFile = new PluginFusinvdeployFile();
$PluginFusinvdeployFilepart = new PluginFusinvdeployFilepart();

if (isset ($_POST["id"]) and $_POST['id']) {
   // Retrieve file informations
   $PluginFusinvdeployFile->getFromDB($_POST['id']);
   
   // Delete file in folder
   $sha512 = $PluginFusinvdeployFile->getField('sha512');
   $filepart = $PluginFusinvdeployFilepart->getForFile($_POST['id']);
   $ids = $PluginFusinvdeployFilepart->getIdsForFile($_POST['id']);
   
   foreach($filepart as $filename => $hash){
      unlink(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$filename);
   }
   
   foreach($ids as $id => $filename){
      $PluginFusinvdeployFilepart->delete(array('id' =>$id));
   }
   
   
   
   // Delete file in DB
   $PluginFusinvdeployFile->delete($_POST);

   // Reply to JS
   echo "{success:true}";
   exit();
}

?>